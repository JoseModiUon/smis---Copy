<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/15/2023
 * @time: 11:37 AM
 */

namespace app\modules\studentRecords\commands;

use app\models\SmWithdrawalApproval;
use app\models\SmWithdrawalRequest;
use app\modules\studentRegistration\helpers\SmisHelper;
use Exception;
use Yii;
use app\modules\setup\utils\AutoSynchronize;
use yii\console\Controller;

class DefermentController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionSync()
    {
        // print('hello dnd');
        $admin = Yii::$app->db->beginTransaction();
        $portal = Yii::$app->db2->beginTransaction();
        SmisHelper::logMessage('Withdrawal Request sync started.', __METHOD__);
        try {

            $auto = new AutoSynchronize();
            $auto->bulkSyncPortal(SmWithdrawalRequest::class);
            $admin->commit();
            $portal->commit();
            SmisHelper::logMessage('Withdrawal Request sync finished.', __METHOD__);
        } catch (Exception $ex) {
            $portal->rollBack();
            $admin->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
        }
    }
}
