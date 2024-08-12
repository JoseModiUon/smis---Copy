<?php

namespace app\modules\courseRegistration\controllers;

use app\models\OrgCourses;
use app\models\search\OrgAddCoursesSearch;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgrammeCurriculum;
use yii\web\NotFoundHttpException;
use app\models\search\OrgProgCurrCourseSearch;
use app\modules\setup\utils\AutoSynchronize;
use Exception;
use kartik\growl\Growl;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

/**
 * OrgProgCurrCourseController implements the CRUD actions for OrgProgCurrCourse model.
 */
class OrgProgCurrCourseController extends Controller
{
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
     * Lists all OrgProgCurrCourse models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrCourseSearch();

        if (null !== Yii::$app->request->get('OrgProgCurrCourseSearch')) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider  = new ActiveDataProvider([
                'query' => OrgProgCurrCourse::find()->where(['prog_curriculum_course_id' => 0])
            ]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single OrgProgCurrCourse model.
     * @param int $prog_curriculum_course_id Prog Curriculum Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_course_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrCourse();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    $autoSync = new AutoSynchronize([$model]);
                    $autoSync->save();

                    $transaction->commit();
                    $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Programme Curriculum Course Created!',
                        'closeButton' => null,
                    ]);
                    return $this->redirect('index');

                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }

        
    }

    /**
     * Updates an existing OrgProgCurrCourse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_course_id Prog Curriculum Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_course_id)
    {
        $model = $this->findModel($prog_curriculum_course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Course Details updated successfully",
                    'closeButton' => null,
                ]);
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrCourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_course_id Prog Curriculum Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_course_id)
    {
        $this->findModel($prog_curriculum_course_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_course_id Prog Curriculum Course ID
     * @return OrgProgCurrCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_course_id)
    {
        if (($model = OrgProgCurrCourse::findOne(['prog_curriculum_course_id' => $prog_curriculum_course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * display add course page
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddCourses($prog_curriculum_id)
    {

        $searchModel = new OrgAddCoursesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, ['prog_curriculum_id' => $prog_curriculum_id]);

        return $this->render('courses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'prog_curriculum_id' => $prog_curriculum_id
        ]);
    }


    /**
     * saves courses to  a curriculum.
     * @param int $prog_curriculum_id Prog Curriculum ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSaveCourses()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $status = $this->saveHelper(Yii::$app->request->post());
            $message = $status ? "course addition successful!" : "course addition failed.";

            $transaction->commit();
            return $this->asJson([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    private function saveHelper(array $input): bool
    {
        ['prog_curriculum_id' => $prog_curriculum_id, 'courseIds' => $courseIds] = $input;
        $currentlySavedCourseIds = OrgProgCurrCourse::find()
            ->select('course_id')->where(['prog_curriculum_id' => $prog_curriculum_id, 'status' => 'ACTIVE'])
            ->asArray()->all();
        $savedIds = ArrayHelper::getColumn($currentlySavedCourseIds, 'course_id');


        /**
         * All ids in the db not in the input need to be removed
         */
        $idsToRemove = array_diff($savedIds, $courseIds);

        /**
         * all ids in the input not in the db need to be added
         */
        $idsToAdd = array_diff($courseIds, $savedIds);
        $statRem = $this->removeCourses($idsToRemove, (int)$prog_curriculum_id);
        $statAdd = $this->addCourses($idsToAdd, (int)$prog_curriculum_id);

        return $statRem && $statAdd;
    }

    private function removeCourses(array $courseIds, int $prog_curriculum_id): bool
    {
        if (empty($courseIds)) {
            return true;
        }

        foreach ($courseIds as $course_id) {
            $course = OrgProgCurrCourse::find()->where(['prog_curriculum_id' => $prog_curriculum_id, 'course_id' => $course_id, 'status' => 'ACTIVE'])->one();
            $course->status = 'INACTIVE';
            $ok = $course->save();
            if (!$ok) {
                break;
            }
        }
        return $ok;
    }

    private function addCourses(array $courseIds, int $prog_curriculum_id): bool
    {
        if (empty($courseIds)) {
            return true;
        }

        $consts = [
            'prog_curriculum_id' => $prog_curriculum_id,
            'credit_factor' => 1,
            'credit_hours' => 60,
            'has_course_work' => true
        ];

        foreach ($courseIds as $course_id) {
            $course = OrgCourses::find()->select([
                'course_id',
                'level_of_study',
                'semester',
                'status'
            ])->where(['course_id' => $course_id, 'status' => 'ACTIVE'])->asArray()->one();
            $model = new OrgProgCurrCourse();
            $this->assign($model, array_merge($consts, (array) $course));
            $ok = $model->save();
            if (!$ok) {
                break;
            }
        }
        return $ok;
    }

    private function assign(&$model, $params)
    {
        foreach ($params as $key => $value) {
            $model->{$key} = $value;
        }
    }
}
