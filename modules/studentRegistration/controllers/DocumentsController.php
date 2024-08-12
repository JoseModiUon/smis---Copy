<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\controllers;

use app\modules\studentRegistration\helpers\SmisHelper;
use app\modules\studentRegistration\models\AdmittedStudent;
use app\modules\studentRegistration\models\Cohort;
use app\modules\studentRegistration\models\Intake;
use app\modules\studentRegistration\models\IntakeSource;
use app\modules\studentRegistration\models\Programme;
use app\modules\studentRegistration\models\search\DocumentsSearch;
use app\modules\studentRegistration\models\SPAdmittedStudent;
use app\modules\studentRegistration\models\SPSubmittedDocument;
use app\modules\studentRegistration\models\StudentCategory;
use app\modules\studentRegistration\models\SubmittedDocument;
use app\modules\studentRegistration\services\RegisterStudents;
use app\traits\ControllerTrait;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class DocumentsController extends BaseController
{
    use ControllerTrait;

    private const REGISTERED_STATUS = 'REGISTERED';
    private const PRE_REGISTERED_STATUS = 'PRE-REGISTRATION';
    private const ACADEMIC_PROGRESS_STATUS_REGISTERED = 'REGISTERED';
    private const ACADEMIC_PROGRESS_ACTIVE = 'ACTIVE';
    private const NEW_STUDENTS_ACADEMIC_LEVEL = 1;
    private const ADMISSION_CLEARANCE_CLEARED_STATUS = 'CLEARED';
    private const ADMISSION_CLEARANCE_NOT_CLEARED_STATUS = 'NOT_CLEARED';
    private const ADMISSION_CLEARANCE_PENDING_STATUS = 'PENDING';
    private const FIRST_PROMOTION_PROGRESS_NUMBER = 1; // A semester progress number counts how many times a student has been promoted. Start it at 1 for new students
    private const DOC_VERIFY_STATUS_APPROVED = 'APPROVED';
    private const DOC_VERIFY_STATUS_NOT_APPROVED = 'NOT_APPROVED';
    private const DOC_VERIFY_STATUS_PENDING = 'PENDING';
    private const STUDENT_SEMESTER_SESSION_STATUS_REPORTED = 'REPORTED';

    /**
     * {@inheritdoc}
     */
    # [ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    # [ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionIndex(): string
    {
        try {
            $docsSearchModel = new DocumentsSearch();
            $docsDataProvider = $docsSearchModel->search(Yii::$app->request->queryParams, [
                'submissionStatus' => true,
                'admissionStatus' => self::PRE_REGISTERED_STATUS,
            ]);

            $intakes = Intake::find()->select(['intake_code', 'intake_name'])->asArray()->all();
            $intakesList = ArrayHelper::map($intakes, 'intake_code', function ($intake) {
                return $intake['intake_name'];
            });

            $intakeSources = IntakeSource::find()->select(['source_id', 'source'])->asArray()->all();
            $intakeSourcesList = ArrayHelper::map($intakeSources, 'source_id', function ($intakeSource) {
                return $intakeSource['source'];
            });

            $programmes = Programme::find()->select(['prog_code', 'prog_full_name'])->asArray()->all();
            $programmesList = ArrayHelper::map($programmes, 'prog_code', function ($programme) {
                return $programme['prog_full_name'];
            });

            $categories = StudentCategory::find()->select(['std_category_id', 'std_category_name'])->asArray()->all();
            $categoriesList = ArrayHelper::map($categories, 'std_category_id', function ($category) {
                return $category['std_category_name'];
            });

            return $this->render('index', [
                'title' => $this->createPageTitle('students'),
                'docsSearchModel' => $docsSearchModel,
                'docsDataProvider' => $docsDataProvider,
                'intakesList' => $intakesList,
                'intakeSourcesList' => $intakeSourcesList,
                'programmesList' => $programmesList,
                'categoriesList' => $categoriesList
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @param string $admRefNo
     * @return Response|string
     * @throws ServerErrorHttpException
     */
    public function actionVerify(string $admRefNo): Response|string
    {
       try{
           $submittedDocuments = SubmittedDocument::find()->alias('sd')
               ->where(['sd.adm_refno' => $admRefNo])
               ->joinWith('requiredDocument rd')
               ->joinWith('requiredDocument.document doc')
               ->asArray()
               ->all();

           return $this->render('verify', [
               'title' => $this->createPageTitle('verify documents'),
               'admRefNo' => $admRefNo,
               'submittedDocuments' => $submittedDocuments,
               'canBeAdmitted' => $this->studentCanBeAdmitted($admRefNo)
           ]);
       }catch (Exception $ex){
           $message = $ex->getMessage();
           if(YII_ENV_DEV){
               $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
           }
           throw new ServerErrorHttpException($message, 500);
       }
    }

    /**
     * @return Response
     */
    public function actionUpdate(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $submittedDocId = $post['submittedDocId'];
            $status = $post['status'];

            if($status !== self::DOC_VERIFY_STATUS_APPROVED && $status !== self::DOC_VERIFY_STATUS_NOT_APPROVED){
                throw new Exception('Invalid document status.');
            }

            $submittedDoc = SubmittedDocument::findOne($submittedDocId);
            $submittedDoc->verify_status = $status;
            $submittedDoc->doc_comments = $post['comments'];
            if(!$submittedDoc->save()){
                $transaction->rollBack();
                $errorMessage = 'Document verify status not saved.';
                if(!$submittedDoc->validate()){
                    $errorMessage = SmisHelper::getModelErrors($submittedDoc->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }
                throw new Exception($errorMessage);
            }

            // Sync the submitted documents on the portal side
            $spSubmittedDoc = SPSubmittedDocument::findOne($submittedDocId);
            $spSubmittedDoc->verify_status = $submittedDoc->verify_status;
            $spSubmittedDoc->doc_comments = $submittedDoc->doc_comments;
            if(!$spSubmittedDoc->save()){
                $transaction->rollBack();
                $spTransaction->rollBack();
                $errorMessage = 'Document verify status failed to sync.';
                if(!$spSubmittedDoc->validate()){
                    $errorMessage = SmisHelper::getModelErrors($spSubmittedDoc->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }
                throw new Exception($errorMessage);
            }

            /**
             * If all mandatory documents submitted have been checked, ie have status APPROVED or NOT_APPROVED,
             * and at least one has not been approved, notify the student and update clearance status to NOT_CLEARED.
             * Otherwise, if all documents are approved then clearance status is updated to PENDING.
             * This clearance status is only updated from PENDING to CLEARED, if the admin clears the student.
             * @check method actionClearStudent
             */
            // make sure all documents have no status PENDING
            $pendingDocuments = SubmittedDocument::find()->alias('sd')
                ->where(['sd.adm_refno' => $submittedDoc->adm_refno, 'sd.verify_status' => self::DOC_VERIFY_STATUS_PENDING])
                ->joinWith('requiredDocument rd')
                ->joinWith('requiredDocument.document doc')
                ->andWhere(['doc.required' => true])
                ->count();

            $redirectToIndex = false;
            if((int)$pendingDocuments === 0){
                $notApprovedDocuments = SubmittedDocument::find()->alias('sd')
                    ->where(['sd.adm_refno' => $submittedDoc->adm_refno, 'sd.verify_status' => self::DOC_VERIFY_STATUS_NOT_APPROVED])
                    ->joinWith('requiredDocument rd')
                    ->joinWith('requiredDocument.document doc')
                    ->andWhere(['doc.required' => true])
                    ->count();

                $admittedStudent = AdmittedStudent::findOne($submittedDoc->adm_refno);

                if((int)$notApprovedDocuments > 0){
                    $admittedStudent->clearance_status = self::ADMISSION_CLEARANCE_NOT_CLEARED_STATUS; // Enables to check for reports of students whose documents have been returned
                    $admittedStudent->doc_submission_status = false; // Enables re-upload for the student
                }else{
                    $admittedStudent->clearance_status = self::ADMISSION_CLEARANCE_PENDING_STATUS;
                }

                if($admittedStudent->save()){
                    if((int)$notApprovedDocuments > 0){
                        $redirectToIndex = true; // No more action is to be done on this student's documents until they re-upload and submit again
                        $this->sendEmailDocsReUpload($admittedStudent->surname, $admittedStudent->primary_email);
                    }
                }else{
                    $transaction->rollBack();
                    if(!$admittedStudent->validate()){
                        $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Changes not saved.');
                    }
                }

                // Sync admitted student data on the portal side
                $spAdmittedStudent = SPAdmittedStudent::findOne($submittedDoc->adm_refno);
                $spAdmittedStudent->clearance_status = $admittedStudent->clearance_status;
                $spAdmittedStudent->doc_submission_status = $admittedStudent->doc_submission_status;
                if(!$spAdmittedStudent->save()){
                    $transaction->rollBack();
                    $spTransaction->rollBack();
                    if(!$spAdmittedStudent->validate()){
                        $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Changes failed to sync.');
                    }
                }
            }

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Verify documents', 'Changes saved successfully.');
            if($redirectToIndex){
                return $this->redirect(['/student-registration/documents/index']);
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionClearStudent(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $admRefNo = $post['admRefNo'];
            if(!$this->studentCanBeAdmitted($admRefNo)){
                return $this->asJson(['success' => false,
                    'message' => 'This student can\'t be admitted yet. All their required documents are not fully approved.']);
            }

            $admittedStudent = AdmittedStudent::findOne($admRefNo);
            $admittedStudent->admission_status = self::REGISTERED_STATUS;
            $admittedStudent->clearance_status = self::ADMISSION_CLEARANCE_CLEARED_STATUS;
            if(!$admittedStudent->save()){
                $transaction->rollBack();
                if(!$admittedStudent->validate()){
                    $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Student was not admitted.');
                }
            }

            // Sync the admitted student with student portal db
            $spAdmittedStudent = SPAdmittedStudent::findOne($admRefNo);
            $spAdmittedStudent->admission_status = $admittedStudent->admission_status;
            $spAdmittedStudent->clearance_status = $admittedStudent->clearance_status;
            if(!$spAdmittedStudent->save()){
                $transaction->rollBack();
                $spTransaction->rollBack();
                if(!$spAdmittedStudent->validate()){
                    $errorMessage = SmisHelper::getModelErrors($spAdmittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Student admission data failed to sync.');
                }
            }

            /**
             * It is possible to have more than one cohort open.
             * We place a student in the cohort that is still open. ie start_date <= current_date <= end_date
             * The student's cohort may be changed later.
             */
            $currentDate = SmisHelper::formatDate('now', 'Y-m-d');
            $cohort = Cohort::find()->select(['cohort_year'])->where(['cohort_status' => 'ACTIVE'])
                ->andWhere(['<=', 'adm_start_date', $currentDate])
                ->andWhere(['>=', 'adm_end_date', $currentDate])
                ->orderBy(['adm_end_date' => SORT_ASC])->asArray()->one();

            $regNumber = $admittedStudent->uon_prog_code . '/' . $admittedStudent->reg_no_serial_no . '/' . $cohort['cohort_year'];

            new RegisterStudents($admittedStudent, $regNumber);

            $this->sendEmailStudentCleared($admittedStudent->surname, $admittedStudent->primary_email, $regNumber);

            $transaction->commit();
            $spTransaction->commit();
            $this->setFlash('success', 'Verify documents', 'Student was registered successfully.');
            return $this->redirect(['/student-registration/documents/index']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Check status of all submitted documents that are mandatory
     * @param string $admRefNo
     * @return bool True if all are APPROVED. False if any is NOT_APPROVED.
     */
    private function studentCanBeAdmitted(string $admRefNo): bool
    {
        $submittedDocuments = SubmittedDocument::find()->alias('sd')
            ->where(['sd.adm_refno' => $admRefNo])
            ->joinWith('requiredDocument rd')
            ->joinWith('requiredDocument.document doc')
            ->andWhere(['doc.required' => true])
            ->count();

        $approvedDocuments = SubmittedDocument::find()->alias('sd')
            ->where(['sd.adm_refno' => $admRefNo, 'sd.verify_status' => self::DOC_VERIFY_STATUS_APPROVED])
            ->joinWith('requiredDocument rd')
            ->joinWith('requiredDocument.document doc')
            ->andWhere(['doc.required' => true])
            ->count();

        if($submittedDocuments === 0){
            return false;
        }

        if((int)$submittedDocuments === (int)$approvedDocuments){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param string $recipientName
     * @param string $recipientEmail
     * @return void
     * @throws Exception
     */
    private function sendEmailDocsReUpload(string $recipientName, string $recipientEmail): void
    {
        $emails = [
            'recipientEmail' => $recipientEmail,
            'subject' => 'REGISTRATION DOCUMENTS UPLOAD',
            'params' => [
                'recipient' => $recipientName,
            ]
        ];
        $layout = '@app/modules/studentRegistration/mail/layouts/html';
        $view = '@app/modules/studentRegistration/mail/views/reUploadDocuments';
        SmisHelper::sendEmails([$emails], $layout, $view);
    }

    /**
     * @param string $recipientName
     * @param string $recipientEmail
     * @param $regNumber
     * @return void
     * @throws Exception
     */
    private function sendEmailStudentCleared(string $recipientName, string $recipientEmail, $regNumber): void
    {
        $emails = [
            'recipientEmail' => $recipientEmail,
            'subject' => 'REGISTRATION DOCUMENTS APPROVAL',
            'params' => [
                'recipient' => $recipientName,
                'regNumber' => $regNumber
            ]
        ];
        $layout = '@app/modules/studentRegistration/mail/layouts/html';
        $view = '@app/modules/studentRegistration/mail/views/studentCleared';
        SmisHelper::sendEmails([$emails], $layout, $view);
    }
}
