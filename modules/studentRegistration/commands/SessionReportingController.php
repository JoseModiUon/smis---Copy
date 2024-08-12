<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/15/2023
 * @time: 1:04 PM
 */

namespace app\modules\studentRegistration\commands;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\SPStudentSemesterSessionProgress;
use app\modules\studentRegistration\models\StudentSemesterSessionProgress;
use Exception;
use Yii;

class SessionReportingController extends BaseController
{
    /**
     * @throws Exception
     */
    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Students session reporting sync started.', __METHOD__);
        try{
            $reportedSessions = SPStudentSemesterSessionProgress::find()->select(['student_semester_session_id'])->where(['reporting_sync_status' => false])
                ->asArray()->all();
            if(count($reportedSessions) > 0) {
                foreach ($reportedSessions as $reportedSession){
                    $semesterSessionId = $reportedSession['student_semester_session_id'];
                    $spStudentSemSessionProgress = SPStudentSemesterSessionProgress::findOne($semesterSessionId);
                    $spStudentSemSessionProgress->reporting_sync_status = true;
                    if(!$spStudentSemSessionProgress->save()){
                        $errorMessage = 'Students session reporting failed to sync.';
                        if(!$spStudentSemSessionProgress->validate()){
                            $errorMessage = SmisHelper::getModelErrors($spStudentSemSessionProgress->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $studentSemSessionProgress = StudentSemesterSessionProgress::findOne($semesterSessionId);
                    $studentSemSessionProgress->setAttributes($spStudentSemSessionProgress->attributes);
                    $studentSemSessionProgress->reporting_snyc_status = true;
                    if(!$studentSemSessionProgress->save()){
                        $errorMessage = 'Students session reporting failed to sync.';
                        if(!$studentSemSessionProgress->validate()){
                            $errorMessage = SmisHelper::getModelErrors($studentSemSessionProgress->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Students session reporting sync finished.', __METHOD__);
        }catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
        }
    }
}
