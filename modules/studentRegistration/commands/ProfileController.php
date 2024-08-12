<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/15/2023
 * @time: 11:37 AM
 */

namespace app\modules\studentRegistration\commands;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\SPAdmittedStudent;
use app\modules\studentRegistration\models\SPStudent;
use app\modules\studentRegistration\models\Student;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use Exception;
use Yii;

class ProfileController extends BaseController
{
    /**
     * @throws Exception
     */
    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Students profile updates sync started.', __METHOD__);
        try{
            $profilesToSync = SPAdmittedStudent::find()->select(['adm_refno'])->where(['profile_sync_status' => false])
                ->asArray()->all();
            if(count($profilesToSync) > 0) {
                foreach ($profilesToSync as $profileToSync){
                    $admRefNo = $profileToSync['adm_refno'];
                    $spAdmittedStudent = SPAdmittedStudent::findOne($admRefNo);
                    $spAdmittedStudent->profile_sync_status = true;
                    if(!$spAdmittedStudent->save()){
                        $errorMessage = 'Admitted student data failed to sync.';
                        if(!$spAdmittedStudent->validate()){
                            $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $admittedStudent = AdmittedStudent::findOne($admRefNo);
                    if($admittedStudent){
                        $admittedStudent->setAttributes($spAdmittedStudent->attributes);
                        if(!$admittedStudent->save()){
                            $errorMessage = 'Admitted student data failed to sync.';
                            if(!$admittedStudent->validate()){
                                $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                            }
                            throw new Exception($errorMessage);
                        }
                    }

                    $studentProgCurr = StudentProgCurriculum::find()->select(['student_id'])->where(['adm_refno' => $admRefNo])
                        ->one();
                    if($studentProgCurr){
                        $studentId = $studentProgCurr->student_id;
                        $spStudent = SPStudent::findOne($studentId);
                        $student = Student::findOne($studentId);
                        $student->setAttributes($spStudent->attributes);
                        if(!$student->save()){
                            $errorMessage = 'Student data failed to sync.';
                            if(!$student->validate()){
                                $errorMessage = SmisHelper::getModelErrors($student->getErrors());
                            }
                            throw new Exception($errorMessage);
                        }
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Students profile updates sync finished.', __METHOD__);
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
