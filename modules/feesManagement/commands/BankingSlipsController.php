<?php


namespace app\modules\feesManagement\commands;

use Yii;
use Exception;

use yii\console\Controller;
use yii\web\ServerErrorHttpException;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\FeePayments;
use app\modules\feesManagement\models\FssPaymentTypes;
use app\modules\studentRegistration\helpers\SmisHelper;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class BankingSlipsController extends  Controller
{
    /**
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Fees Posting started.', __METHOD__);
        try {

            $records = BankingSlips::find()->where(['!=', 'post_status', 'POSTED'])->all();
            $ok = false;
            if (count($records) > 0) {
                foreach ($records as $rec) {
                    $fee_type = FssPaymentTypes::findOne(['payment_type_id'=> $rec->deposit_type]);
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
                        echo "record {$rec->trans_id} transferred successfully".PHP_EOL;
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Fee posting finished.', __METHOD__);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
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
