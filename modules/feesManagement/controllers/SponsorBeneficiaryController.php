<?php

namespace app\modules\feesManagement\controllers;

use Yii;
use kartik\growl\Growl;
use yii\web\Controller;
use app\models\SmStudent;
use app\models\Student;
use app\modules\feesManagement\models\BankingSlips;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\feesManagement\models\ReceiptSponsorFund;
use app\modules\feesManagement\models\search\SponsorBeneficiarySearch;
use app\modules\feesManagement\models\SponsorBeneficiary;
use app\modules\feesManagement\traits\FeeTrait;
use yii\db\Transaction;
use yii\web\Response;

/**
 * SponsorBeneficiaryController implements the CRUD actions for SponsorBeneficiary model.
 */
class SponsorBeneficiaryController extends Controller
{
    use FeeTrait;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'find-name') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'remove-beneficiary') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SponsorBeneficiary models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SponsorBeneficiarySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $model = new SponsorBeneficiary;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single SponsorBeneficiary model.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sponsor_beneficiary_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sponsor_beneficiary_id),
        ]);
    }


    public function sponsorCreator($post): SponsorBeneficiary|false
    {
        $model = SponsorBeneficiary::find()->where([
            'receipt_sponsor_fund_id' =>  $post['receipt_sponsor_fund_id'],
            'reg_no' => $post['reg_no']
        ])->one();
        if (!$model) {
            $model = new SponsorBeneficiary();
            $this->assign($model, $post);
            $receiptfund = ReceiptSponsorFund::findOne($model->receipt_sponsor_fund_id);
            if ($receiptfund->findBalance() > $model->amount && $receiptfund->findBalance() - $model->amount >= 0) {
                return $model;
            }
            return false;
        }
        return false;
    }

    public function actionBulkCreator(array $records, array $post): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $beneficiaries = [];

            $ok = false;
            foreach ($records as $rec) {
                $record = [
                    'reg_no' => $rec[0],
                    'amount' => $rec[1],
                    'receipt_sponsor_fund_id' => $post['receipt_sponsor_fund_id'],
                    'name' =>  $this->findName($rec[0]),
                    "transfer_status" => "PENDING",
                    "trans_type" => "POST",
                    "post_date" => date('Y-m-d'),
                    "user_id" => Yii::$app->user->identity->username,
                    ...$post
                ];
                $model = $this->sponsorCreator($record);
                $ok = $model->save();
                if (!$ok) break;
                $model->refresh();
                $beneficiaries[] = $model;
            }
            if ($ok) {

                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => 'Beneficiary Created Successfully!',
                    'closeButton' => null,
                ]);
                $transaction->commit();
            } else {
                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_DANGER,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => 'Beneficiary Not Created.',
                    'closeButton' => null,
                ]);
            }
            return $beneficiaries;
        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }
    }
    /**
     * Creates a new SponsorBeneficiary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->request->isPost) {

                $model = $this->sponsorCreator($this->request->post('SponsorBeneficiary'));
                $ok = false;
                if ($model) $ok =  $model->save();
                $transaction->commit();

                $type = $ok ? Growl::TYPE_SUCCESS : Growl::TYPE_DANGER;
                $message = $ok ? 'Sponsor Beneficiary Created' : 'Could not add beneficiary';

                Yii::$app->getSession()->setFlash('new', [
                    'type' => $type,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $message,
                    'closeButton' => null,
                ]);
                return $this->redirect(['/fees-management/receipt-sponsor-fund/list-beneficiary-view', 'receipt_sponsor_fund_id' => $this->request->post('SponsorBeneficiary')['receipt_sponsor_fund_id']]);
            } else {
                $model = (new SponsorBeneficiary())->loadDefaultValues();
            }

            return $this->render('create', [
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

    /**
     * Remove beneficiary of a fund to be receipted.
     *
     * @return string
     */
    public function actionRemoveBeneficiary()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->request->isPost) {
                $ok = false;
                $msg = '';

                $ids = $this->request->post('sponsor_beneficiary_ids');
                foreach ($ids as $id) {
                    $beneficiary = SponsorBeneficiary::findOne($id);
                    $banking = BankingSlips::find()->where(['sponsor_beneficiary_id' => $id])->one();
                    if ($banking) {
                        $ok = $banking->delete() && $beneficiary->delete();
                    } else {
                        $ok = $beneficiary->delete();
                    }
                }


                if ($ok) {
                    $msg = 'Beneficiary successfully removed';
                    $transaction->commit();
                } else {
                    $msg = 'Beneficiary removal failed';
                    $transaction->rollBack();
                }

                return $this->asJson(['success' => $ok, 'msg' => $msg]);
            }
        } catch (\Throwable $th) {
            $transaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionUpdateBeneficiary()
    {

        $model = SponsorBeneficiary::findOne($this->request->get('sponsor_beneficiary_id'));
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->request->isPost) {

                $ok = $model->updateBeneficiary();
                if ($ok) {
                    $transaction->commit();
                }


                $type = $ok ? Growl::TYPE_SUCCESS : Growl::TYPE_DANGER;
                $message = $ok ? 'Sponsor Beneficiary Updated' : 'Could not update sponsor beneficiary';

                Yii::$app->getSession()->setFlash('new', [
                    'type' => $type,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $message,
                    'closeButton' => null,
                ]);
                return $this->redirect(['/fees-management/receipt-sponsor-fund/list-beneficiary-view', 'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id]);
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('update', [
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

    private function findName(string $reg_no): string
    {

        $stu = Student::find()->where(['student_number' => $reg_no])->one();
        if ($stu) {
            $other_names = $stu->other_names;
            $surname = $stu->surname;
            return $surname . ' ' . $other_names;
        }
        return '';
    }


    /**
     * find student name
     *
     * @return JsonResponse
     */
    public function actionFindName()
    {
        try {
            $data = $this->request->post('data');
            $name = $this->findName($data['student_reg_no']);
            return $this->asJson(['name' => $name]);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    // Get the student name action
    public function actionStudentName()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Retrieve the registration number from the AJAX request
        $reg_no = Yii::$app->request->post('student_reg_no');

        $student = SmStudent::find()->select(['surname', 'other_names'])->where(['student_number' => $reg_no])->one();
        if ($student) {
            // Concatenate to get fullname
            $student_name = $student->surname . ' ' . $student->other_names;
            // Return the student 
            return ['success' => true, 'student_name' => $student_name];
        } else {
            // Return an error message if the student is not found
            return ['success' => false, 'error' => 'Student not found'];
        }
    }


    /**
     * Updates an existing SponsorBeneficiary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sponsor_beneficiary_id)
    {
        $model = $this->findModel($sponsor_beneficiary_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SponsorBeneficiary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sponsor_beneficiary_id)
    {
        $this->findModel($sponsor_beneficiary_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SponsorBeneficiary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return SponsorBeneficiary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sponsor_beneficiary_id)
    {
        if (($model = SponsorBeneficiary::findOne(['sponsor_beneficiary_id' => $sponsor_beneficiary_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
