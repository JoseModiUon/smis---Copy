<?php

namespace app\modules\feesManagement\controllers;

use app\models\Student;
use Yii;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\FeePayments;
use app\modules\feesManagement\models\FeeTransactions;
use app\modules\feesManagement\models\FssReceiptInformation;
use app\modules\feesManagement\models\ReceiptSponsorFund;
use app\modules\feesManagement\models\search\BankingSlipsSearch;
use app\modules\feesManagement\models\search\ReportsSearch;
use app\modules\feesManagement\models\search\ReportsStatementSearch;
use app\modules\feesManagement\models\SponsorBeneficiary;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use kartik\growl\Growl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;

/**
 * BankingSlipsController implements the CRUD actions for BankingSlips model.
 */
class ReportsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'find-funds') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Open Fee Statement
     * @return mixed
     */
    public function actionFeeStatement()
    {
        $searchModel = new StudentProgCurriculum();
        if (!empty($this->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => BankingSlips::find()->where(['trans_id' => 0]),
            ]);
        }

        return $this->render('fee-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // private function findFunds(string $id): array
    // {
    //     $funds = ReceiptSponsorFund::find()->select(['receipt_sponsor_fund_id', 'description'])
    //         ->where(['sponsor_id' => $id])->all();
    //     return ArrayHelper::map($funds, 'receipt_sponsor_fund_id', 'description');
    // }

    public function actionFindFunds()
    {
        try {
            $data = $this->request->post('data');
            $funds = $this->findFunds($data['sponsor_id']);
            return $this->asJson($funds);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function actionViewStatement()
    {
        return $this->render('fee-statement', [
            'data' => $this->findFeeData(),
        ]);
        
    }

    private function findFeeData(): array
    {
        $db = Yii::$app->db;
        $searchstudquery = "SELECT
        SM_STUDENT.OTHER_NAMES||' '||
      SM_STUDENT.SURNAME AS student_names, 
        sm_student.student_number AS registration_number, 
        org_academic_session.acad_session_name AS academic_year, 
        fss_fee_transactions.trans_desc, 
        fss_fee_transactions.trans_id, 
        fss_fee_transactions.trans_date, 
        CASE UPPER(fss_fee_transactions.TRANS_TYPE) WHEN 'DR' THEN fss_fee_transactions.TRANS_AMOUNT  ELSE 0 END AS debits, 
        CASE UPPER(fss_fee_transactions.TRANS_TYPE) WHEN 'CR' THEN fss_fee_transactions.TRANS_AMOUNT ELSE 0 END AS credits, 
        'KES' AS currency_id, 
        COALESCE(fss_fee_transactions.EXCHANGE_RATE,1) AS exchange_rate
    FROM
        smis.fss_fee_transactions
        INNER JOIN
        smis.fss_fee_payments
        ON 
            fss_fee_payments.trans_id = fss_fee_transactions.trans_id
        INNER JOIN
        smis.sm_student_programme_curriculum
        ON 
            fss_fee_payments.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
        INNER JOIN
        smis.sm_academic_progress
        ON 
            sm_academic_progress.student_prog_curriculum_id = sm_student_programme_curriculum.student_prog_curriculum_id
        INNER JOIN
        smis.org_academic_session
        ON 
            org_academic_session.acad_session_id = sm_academic_progress.acad_session_id
        INNER JOIN
        smis.sm_student
        ON 
            sm_student.student_id = sm_student_programme_curriculum.student_id
    WHERE
        (
            sm_student.student_number = :reg_no 
            AND
            COALESCE(fss_fee_transactions.receipt_status, ' ') NOT IN ('CANCELLED','REVERSED')
        )
    GROUP BY
    sm_student.student_number,
    student_names,
    org_academic_session.acad_session_id,
    fss_fee_transactions.trans_desc,
    fss_fee_transactions.trans_id, 
    fss_fee_transactions.trans_date,
    fss_fee_transactions.TRANS_TYPE,fss_fee_transactions.TRANS_AMOUNT,fss_fee_transactions.EXCHANGE_RATE
    ORDER BY
        org_academic_session.acad_session_name ASC, 
        fss_fee_transactions.trans_id ASC, 
        fss_fee_transactions.trans_date ASC";
        $reqData = $this->request->get();
        $params = [":reg_no" => $reqData['reg_number']];

        $data = $db->createCommand($searchstudquery)->bindValues($params)->queryAll();
        return $this->sortAndCreateStatement($data);
    }

    private function sortAndCreateStatement(array $data): array
    {
        // find all the years present
        $academicYears = array_unique(ArrayHelper::getColumn($data, 'academic_year'));

        // organize all the data according to years

        $organizedData = $this->executeSort($academicYears, $data);
        return $this->calcuteStatementDetails($organizedData);
    }

    private function executeSort(array $academicYears, array $data): array
    {
        $sortedData = [];
        sort($academicYears);
        foreach ($academicYears as $year) {
            foreach ($data as $row) {
                if ($row['academic_year'] == $year) {
                    $sortedData[$year][] = $row;
                }
            }
        }
        return $sortedData;
    }

    private function calcuteStatementDetails(array $data): array
    {
        $totalDebits = 0;
        $totalCredits = 0;
        $balance = 0;
        $closingBalance = 0;

        // find the total for each academic year
        foreach ($data as $key => $row) {
            if (!ArrayHelper::keyExists($data[$key], 'totalDebits')) {
                $data[$key]['totalDebits'] = 0;
            }
            if (!ArrayHelper::keyExists($data[$key], 'totalCredits')) {
                $data[$key]['totalCredits'] = 0;
            }
            if ($data[$key] !== $data[array_key_first($data)]) {
                $data[$key]['openingBalance'] = $closingBalance;
            }

            foreach ($row as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $totalDebits += $item['debits'];
                $totalCredits += $item['credits'];
                $data[$key]['totalDebits'] = $totalDebits;
                $data[$key]['totalCredits'] = $totalCredits;

                if ($this->findLast($row) === $item) {
                    // if($key == '2007/2008')dd($this->findLast([$data[$key]]));
                    if (array_key_exists('openingBalance', $data[$key])) {
                        $balance = ($data[$key]['totalDebits'] + $data[$key]['openingBalance']) - $data[$key]['totalCredits'];
                    } else {
                        $balance = $data[$key]['totalDebits'] - $data[$key]['totalCredits'];
                    }
                    $data[$key]['balance'] = $balance;
                    $closingBalance = $balance;
                    $totalDebits = 0;
                    $totalCredits = 0;
                }
            }
        }
        return $data;
    }

    private function findLast($row)
    {
        $data = array_filter($row, function ($item) {
            return is_array($item);
        });
        return end($data);
    }

    public function arraySum($array, $string)
    {
        return array_sum(array_column($array, $string));
    }

    public function actionReversal($trans_id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = BankingSlips::findOne(['trans_id' => $trans_id]);
            $receipt_info_model = FssReceiptInformation::find()->where(['trans_id' => $trans_id])->one();
            $active = false;

            if ($receipt_info_model && $receipt_info_model->reverse_approval === 'YES') {
                $active = true;
            } elseif($receipt_info_model && $receipt_info_model->reverse_approval === 'NO') {
                $active = false;
            }

            $post = Yii::$app->request->post();
            if ($post) {
                if ($post['value'] === 'update') {
                    $values = $post['BankingSlips'];
                    $receipt_info_model = new FssReceiptInformation();
                    $receipt_info_model->trans_id = $trans_id;
                    $receipt_info_model->trans_date = $values['deposit_date'];
                    $receipt_info_model->student_regno = $values['reg_number'];
                    $receipt_info_model->reverse_approval = $values['reversal'];
                    $receipt_info_model->allow_duplicate = $values['duplicate'];
                    $receipt_info_model->amount = $values['deposit_amount'];
                    $ok = $receipt_info_model->save();
                    if ($ok) {
                        $transaction->commit();
                    }
                } elseif ($post['value'] === 'reverse') {

                    $fee_transaction = FeeTransactions::find()->where(['trans_id' => $trans_id])->one();
                    $fee_transaction->receipt_status = 'CANCELLED';

                    $payment = FeePayments::find()->where(['trans_id' => $trans_id])->one();
                    $payment->receipt_status = 'CANCELLED';

                    $banking = BankingSlips::find()->where(['trans_id' => $trans_id])->one();
                    $banking->post_status = 'REVERSED';

                    $banking->post_comment = 'Reversed';

                    $ok = $fee_transaction->save() && $payment->save() && $banking->save();

                    if ($ok) {
                        $transaction->commit();
                        $msg = 'Receipt reversed successfully!';
                        Yii::$app->getSession()->setFlash('new', [
                            'type' => Growl::TYPE_SUCCESS,
                            'icon' => 'bi bi-check-circle-fill',
                            'message' => $msg,
                            'closeButton' => null,
                        ]);
                    } else {
                        $msg = 'Error occured during reversal of receipt';
                        Yii::$app->getSession()->setFlash('new', [
                            'type' => Growl::TYPE_DANGER,
                            'icon' => 'bi bi-check-circle-fill',
                            'message' => $msg,
                            'closeButton' => null,
                        ]);
                    }
                }
                return $this->redirect(['/fees-management/banking-slips']);
            }
        } catch (\Throwable $th) {
            $transaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

        return $this->render('reversal.php', [
            'title' => 'Reversal',
            'model' => $model,
            'active' => $active
        ]);
    }

    public function actionUpdateNarrative($trans_id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = BankingSlips::findOne(['trans_id' => $trans_id]);
            $post = Yii::$app->request->post();

            if ($post) {
                $values = $post['BankingSlips'];
                //Update Banking Slips with form values except duplicate, reversal and reinstate
                $model->reg_number = $values['reg_number'];
                $model->drawer_name = $values['drawer_name'];
                $model->chq_no = $values['chq_no'];
                $model->branch_code = $values['branch_code'];
                $model->bank_id = $values['bank_id'];
                $model->pay_mode = $values['pay_mode'];
                $ok = $model->save();
                if ($ok) {
                    $transaction->commit();
                    $msg = 'Narrative updated successfully!';
                    Yii::$app->getSession()->setFlash('new', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => $msg,
                        'closeButton' => null,
                    ]);
                } else {
                    $msg = 'Error occured during updating narrative';
                    Yii::$app->getSession()->setFlash('new', [
                        'type' => Growl::TYPE_DANGER,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => $msg,
                        'closeButton' => null,
                    ]);
                }
                return $this->redirect('/fees-management/banking-slips');
            }

            return $this->render('update-narrative', [
                'title' => 'Update Narrative/Reference',
                'model' => $model,

            ]);
        } catch (\Throwable $th) {
            $transaction->rollBack();

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionSearchReceipt()
    {
        $model = new BankingSlips();

        return $this->render('search-receipt', [
            'title' => 'Search Receipt',
            'model' => $model
        ]);
    }
}
