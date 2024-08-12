<?php

namespace app\modules\feesManagement\controllers;

// use app\helpers\SmisHelper;
use app\modules\feesManagement\commands\BankingSlipsController as CommandsBankingSlipsController;
use Yii;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\search\BankingSlipsSearch;
use app\modules\feesManagement\models\SponsorBeneficiary;
use kartik\growl\Growl;
use NumberFormatter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;
// use Yii;
use Exception;

// use yii\console\Controller;
// use yii\web\ServerErrorHttpException;
// use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\FeePayments;
use app\modules\feesManagement\models\FssPaymentTypes;
use app\modules\studentRegistration\helpers\SmisHelper;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * BankingSlipsController implements the CRUD actions for BankingSlips model.
 */
class BankingSlipsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'post-fee-payments') {
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
     * Lists all BankingSlips models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankingSlipsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => 'Banking Slips'
        ]);
    }

    /**
     * posting fees to transactions and fee payments
     *
     * @return string|yii/web/Response
     */
    public function actionPostFeePayments()
    {
        $narration = $this->request->post('data')['narration'];

        $trans_ids = $this->request->post('data')['trans_ids'];
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $ok = false;
            foreach ($trans_ids as $id) {
                $model = BankingSlips::findOne($id);
                if ($model->post_status == 'POSTED') continue;
                $model->post_comment = $narration;
                $ok = $model->save() && $model->postFeePayments();
                if (!$ok) break;
            }

            if ($ok) {
                $transaction->commit();
                $msg = 'Posting fees completed successfully!';
                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $msg,
                    'closeButton' => null,
                ]);
            } else {
                $msg = 'Posting fees action not be completed.';

                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_DANGER,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => $msg,
                    'closeButton' => null,
                ]);
            }

            return $this->asJson(['status' => $ok, 'msg' => $msg]);
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
     * update banking slip record form
     *
     * @return void
     */
    public function actionUpdateBankingSlip()
    {
        $trans_id = $this->request->get('trans_id');
        $model = BankingSlips::findOne($trans_id);

        if ($this->request->isPost) {

            $ok = $model->updateBankingSlip();

            if ($ok) {

                Yii::$app->getSession()->setFlash('new', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => 'record  updated successfully!',
                    'closeButton' => null,
                ]);
                return $this->redirect(['index']);
            }

            Yii::$app->getSession()->setFlash('new', [
                'type' => Growl::TYPE_DANGER,
                'icon' => 'bi bi-check-circle-fill',
                'message' => 'update action not be completed.',
                'closeButton' => null,
            ]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionReceipt()
    {
        try {
            $get = $this->request->get();
            if ($get === 'BankingSlips') {
                $trans_id = $get['BankingSlips'];
            } else {
                $trans_id = $get;
            }
            $model = BankingSlips::findOne(['trans_id' => $trans_id]);

            return $this->render('receipt', [
                'title' => 'Receipt',
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionCronPostFee()
    {
        $cronJob = $this->actionSync();
    }

    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Posting started.<br>', __METHOD__);
        try {

            $records = BankingSlips::find()->where(['!=', 'post_status', 'POSTED'])->all();
            $ok = false;
            if (count($records) > 0) {
                foreach ($records as $rec) {
                    $fee_type = FssPaymentTypes::findOne(['payment_type_id' => $rec->deposit_type]); //should be commented out after records in table deleted
                    if (!$fee_type) {
                        continue;
                    }
                    if ($rec->post_status === 'REVERSED') {
                        continue;
                    }
                    $fee_type = FssPaymentTypes::findOne(['payment_type_id' => $rec->deposit_type]);

                    $rec->post_comment = $fee_type->payment_desc;

                    $ok = $rec->save() && $rec->postFeePayments();
                    if (!$ok) {
                        $errorMessage = 'Fee posting failed';
                        if (!$rec->validate()) {
                            $errorMessage = SmisHelper::getModelErrors($rec->getErrors());
                        }
                        SmisHelper::logMessage($errorMessage, __METHOD__, 'error');
                        throw new Exception($errorMessage);
                    } else {
                        //should show the reg_no, amount and post_status

                        echo "Fee: {$rec->reg_number}, {$rec->deposit_amount}, {$rec->post_status} posted successfully <br></br>" . PHP_EOL;
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Fee posting finished.', __METHOD__);

            Yii::$app->getSession()->setFlash('new', [
                'type' => Growl::TYPE_SUCCESS,
                'icon' => 'bi bi-check-circle-fill',
                'message' => 'Posting Completed Successfully.',
                'closeButton' => null,
            ]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();

            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
            Yii::$app->getSession()->setFlash('new', [
                'type' => Growl::TYPE_DANGER,
                'icon' => 'bi bi-check-circle-fill',
                'message' => $message,
                'closeButton' => null,
            ]);
        }
    }

    private function checkunposted()
    {
        $query = "SELECT  fss_banking_slips.trans_id FROM  smis.fss_banking_slips WHERE  fss_banking_slips.trans_id NOT IN 
        (SELECT  fss_fee_payments.trans_id FROM  smis.fss_fee_payments)";
        $res = Yii::$app->getDb()->createCommand($query)->queryAll();
        dd($res);
    }
}
