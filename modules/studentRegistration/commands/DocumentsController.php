<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2023
 * @time: 11:57 AM
 */

namespace app\modules\studentRegistration\commands;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\SPAdmittedStudent;
use app\modules\studentRegistration\models\SPSubmittedDocument;
use app\modules\studentRegistration\models\SubmittedDocument;
use Exception;
use Yii;
use yii\web\ServerErrorHttpException;

class DocumentsController extends BaseController
{
    /**
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function actionSync()
    {
        $transaction = Yii::$app->db->beginTransaction();
        SmisHelper::logMessage('Registration documents sync started.', __METHOD__);
        try{
            $admittedStudentsToSync = SPAdmittedStudent::find()->select(['adm_refno'])->where(['document_sync_status' => false])
                ->asArray()->all();

            if(count($admittedStudentsToSync) > 0){
                foreach ($admittedStudentsToSync as $admittedStudentToSync){
                    $admRefNo = $admittedStudentToSync['adm_refno'];
                    $spAdmittedStudent = SPAdmittedStudent::findOne($admRefNo);
                    $spAdmittedStudent->document_sync_status = true;
                    if(!$spAdmittedStudent->save()){
                        $errorMessage = 'Admitted student data failed to sync.';
                        if(!$spAdmittedStudent->validate()){
                            $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $admittedStudent = AdmittedStudent::findOne($admRefNo);
                    $admittedStudent->doc_submission_status = $spAdmittedStudent->doc_submission_status;
                    $admittedStudent->document_sync_status = $spAdmittedStudent->document_sync_status;
                    if(!$admittedStudent->save()){
                        $errorMessage = 'Admitted student data failed to sync.';
                        if(!$admittedStudent->validate()){
                            $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                        }
                        throw new Exception($errorMessage);
                    }

                    $submittedDocsCount = SubmittedDocument::find()->where(['adm_refno' => $admRefNo])->count();
                    if($submittedDocsCount > 0){
                        $deletedDocsCount = SubmittedDocument::deleteAll('adm_refno = ' . $admRefNo);
                        if($deletedDocsCount != $submittedDocsCount){
                            throw new ServerErrorHttpException('Submitted documents data failed to sync.', 500);
                        }
                    }

                    $spSubmittedDocsIds = SPSubmittedDocument::find()->select(['student_document_id'])->where(['adm_refno' => $admRefNo])
                        ->asArray()->all();
                    if(count($spSubmittedDocsIds) > 0){
                        foreach ($spSubmittedDocsIds as $spSubmittedDocId){
                            $submittedDocId = $spSubmittedDocId['student_document_id'];
                            $spSubmittedDoc = SPSubmittedDocument::findOne($submittedDocId);

                            $submittedDoc = new SubmittedDocument();
                            $submittedDoc->setAttributes($spSubmittedDoc->attributes);
                            if(!$submittedDoc->save()){
                                $errorMessage = 'Submitted documents data failed to sync.';
                                if(!$submittedDoc->validate()){
                                    $errorMessage = SmisHelper::getModelErrors($submittedDoc->getErrors());
                                }
                                throw new Exception($errorMessage);
                            }
                        }
                    }
                }
            }
            $transaction->commit();
            SmisHelper::logMessage('Registration documents sync finished.', __METHOD__);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            SmisHelper::logMessage($message, __METHOD__, 'error');
        }
    }
}
