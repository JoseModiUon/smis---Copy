<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 12/11/2023
 * @time: 10:57 AM
 */
declare(strict_types=1);

namespace app\modules\admissions\controllers;

set_time_limit(0);

use app\modules\studentRegistration\models\Intake;
use app\modules\studentRegistration\models\IntakeSource;
use app\modules\studentRegistration\models\StudentCategory;
use app\modules\studentRegistration\models\StudentSponsor;
use app\modules\studentRegistration\models\StudyCentre;
use app\modules\studentRegistration\models\StudyGroup;
use app\modules\studentRegistration\services\UploadExistingStudents;
use app\traits\ControllerTrait;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class StudentsController extends Controller
{
    use ControllerTrait;

    private const ADMISSION_CLEARANCE_CLEARED_STATUS = 'CLEARED';
    private const REGISTERED_STATUS = 'REGISTERED';
    private array $excelStudent = [];

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionUploadExisting(): string
    {
        try {
            return $this->render('uploadExisting', [
                'title' => $this->createPageTitle('Upload existing students')
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function actionSaveExisting(): Response
    {
        if (empty($_FILES)) {
            $this->setFlash('danger', 'Admissions', 'No template selected for upload.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        $duplicateStudents = (new UploadExistingStudents())->save($_FILES);

        if (empty($duplicateStudents)) {
            $this->setFlash('success', 'Upload students', 'Students uploaded and saved successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } else {
            return $this->asJson([
                'success' => true,
                'message' => 'Students uploaded successfully but duplicates were found.',
                'duplicateStudents' => $duplicateStudents
            ]);
        }
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionUploadMapper(): string
    {
        try {
            $studyCenters = StudyCentre::find()->asArray()->all();
            $studyGroups = StudyGroup::find()->asArray()->all();
            $studentCategories = StudentCategory::find()->asArray()->all();
            $intakes = Intake::find()->asArray()->all();
            $intakeSources = IntakeSource::find()->asArray()->all();
            $sponsors = StudentSponsor::find()->asArray()->all();
            $semesters = (new Query())
                ->select([
                    'ass.acad_session_semester_id',
                    'ass.semester_code',
                    'year.acad_session_name'
                ])
                ->from('smis.org_academic_session_semester ass')
                ->innerJoin('smis.org_academic_session year', 'year.acad_session_id=ass.acad_session_id')
                ->all();

            return $this->render('uploadMapper', [
                'title' => $this->createPageTitle('Upload students mapper'),
                'studyCenters' => $studyCenters,
                'studyGroups' => $studyGroups,
                'studentCategories' => $studentCategories,
                'intakes' => $intakes,
                'intakeSources' => $intakeSources,
                'sponsors' => $sponsors,
                'semesters' => $semesters
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response|\yii\console\Response
     * @throws ServerErrorHttpException
     */
    public function actionTemplateDownload(): Response|\yii\console\Response
    {
        try {
            $filename = Yii::getAlias('@uploadExistingStudentsPath') . '/upload_students_data_template.xlsx';
            return Yii::$app->response->sendFile($filename, 'upload_students_data_template.xlsx', ['inline' => true]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}