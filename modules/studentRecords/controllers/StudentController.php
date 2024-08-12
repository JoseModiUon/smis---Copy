<?php

namespace app\modules\studentRecords\controllers;

use app\components\Photo;
use app\models\search\SmStudentProgrammeCurriculumSearch;
use app\models\Student;
use app\models\search\StudentSearch;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'photo-upload' => ['POST'],
                        'photo' => ['GET'],
                        'next-of-kin' => ['POST'],
                        'personal-info' => ['POST'],
                        'contact-info' => ['POST'],
                    ],
                ],
            ],
        );
    }

    /**
     * Lists all Student models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
     * @param string $student_id Student ID
     * @return Response
     */
    public function actionView(string $student_id): Response
    {
        return $this->redirect(['details', 'student_id' => $student_id]);
//        return $this->render('view', [
//            'model' => $this->findModel($STUDENT_ID),
//        ]);
    }

    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Student();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'student_id' => $model->student_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Photo Upload endpoint for Students
     * @param $id
     * @return string[]
     */
    #[ArrayShape(['output' => "string", 'message' => "string"])]
    public function actionPhotoUpload($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $fileName = str_replace('/', '', $id);
        $file = $_FILES['stdn-avatar-input'];

        $target_dir = Yii::getAlias('@photos') . '/students/';
        @mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $fileName;

        $toWidth = Yii::$app->params['image']['default']['width'];
        $toHeight = Yii::$app->params['image']['default']['height'];

        $fileName = $file['name'];
//        $tmpName  = $file['tmp_name'];
//        $fileSize = $file['size'];
//        $fileType = $file['type'];
        $image = new Photo();
        $image->load($file['tmp_name']);
//        $image->resizeToWidth($toWidth);
        $image->resizeToHeight($toHeight);

        $ext = explode('.', $fileName);
        $ext = strtolower(array_pop($ext));
        $image->unlink($target_file); // Remove any previous image
        $image->save($target_file . '.' . $ext);

        return ['output' => '', 'message' => ''];
    }

    /**
     * Get the Student's Photo
     * @param $id
     * @return int|string
     * @throws NotFoundHttpException
     */
    public function actionPhoto($id): int|string
    {
        $model = $this->findModel($id);
        return $model->avatar();
    }

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $student_id Student ID
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $student_id): Student
    {
        if (($model = Student::findOne(['student_id' => $student_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays a single Student model.
     * @param string $student_id Student ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetails(string $student_id): string
    {
        $model = $this->findModel($student_id);

        return $this->render('details', [
            'model' => $model,
            'student_id' => $student_id,
        ]);
//        return $this->render('details', ['STUDENT_ID' => $STUDENT_ID]);
    }

    /**
     * @param $id
     * @param bool $view
     * @return array|string|string[]
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionPersonalInfo($id, bool $view=false): array|string
    {
        if (isset($_POST['hasEditable'])) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model = Student::findOne($id);
            $name = $model->formName();
            $oMsg = '';
            $posted = ($_POST[$name]);
            $post = [$name => $posted];
            if ($model->load($post)) {
                if (isset($posted['NATIONALITY']))
                    $oMsg = (new CountryFlag)->get($model->nationality->CODE2) . ' ' . $model->nationality->NATIONALITY;
                if (isset($posted['SPONSOR']))
                    $oMsg = $model->sponsor->SPONSOR_NAME;
                if (isset($posted['SURNAME']) || isset($posted['OTHER_NAMES']))
                    $oMsg = $model->SURNAME . ' ' . $model->OTHER_NAMES;
                if ($model->save()) {
                    // return JSON encoded output in the below format on success with an empty `message`
                    return ['output' => $oMsg, 'message' => ''];
                } else {
                    // alternatively you can return a validation error (by entering an error message in `message` key)
                    return ['output' => '', 'message' => 'Error: [' . implode(', ', array_values($model->getFirstErrors())) . ']'];
                }
            }

        }
        return Json::encode($this->renderAjax('details/_profile', [
                'model' => $this->findModel($id), 'canEdit' => !$view]
        ));
    }

    /**
     * @param $id
     * @return string
     */
    public function actionNextOfKin($id): string
    {
//        return Json::encode($this->renderAjax('details/next_of_kin'));
        return Json::encode($this->renderAjax('details/_nok'));
    }

    /**
     * @param $id
     * @return string
     */
    public function actionFeesStatement($id): string
    {
        return Json::encode($this->renderAjax('details/_fee'));
    }

    /**
     * @param $id
     * @return string
     */
    public function actionProgramme($id): string
    {
        $searchModel = new SmStudentProgrammeCurriculumSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['student_id' => $id]);
        $dataProvider->pagination = false;
        $dataProvider->sort = false;
        return Json::encode($this->renderPartial('details/_programme', [
            'dataProvider' => $dataProvider,
        ]));
    }
}
