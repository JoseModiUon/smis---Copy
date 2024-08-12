<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\ProgCurrGroupReqCourse;
use app\modules\examManagement\models\ProgrammeRequirement;
use app\modules\examManagement\models\search\ProgCurrGroupReqCourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * ProgCurrGrpReqCourseController implements the CRUD actions for ProgCurrGroupReqCourse model.
 */
class ProgCurrGrpReqCourseController extends Controller
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
     * Lists all ProgCurrGroupReqCourse models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgCurrGroupReqCourseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $prog_curr_group_requirement_id = $_GET['pcgrcid'];
        $dt = ProgrammeRequirement::GET_CURR_GROUP_REQ_COURSES($prog_curr_group_requirement_id);

        $dataexport =   new ArrayDataProvider([
         'pagination' => [
             'pageSize' => 200,
         ],
         'allModels' => $dt]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataexport,
        ]);
    }

    /**
     * Displays a single ProgCurrGroupReqCourse model.
     * @param int $prog_curr_group_req_course_id Prog Curr Group Req Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curr_group_req_course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curr_group_req_course_id),
        ]);
    }

    /**
     * Creates a new ProgCurrGroupReqCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrGroupReqCourse();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgCurrGroupReqCourse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curr_group_req_course_id Prog Curr Group Req Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curr_group_req_course_id)
    {
        $model = $this->findModel($prog_curr_group_req_course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgCurrGroupReqCourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curr_group_req_course_id Prog Curr Group Req Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curr_group_req_course_id)
    {
        $model = new ProgCurrGroupReqCourse();
        $this->findModel($prog_curr_group_req_course_id)->delete();

        return $this->redirect(['index',
            'prog_study_level' => $_REQUEST['prog_study_level'],
            'prog_code' => $_REQUEST['prog_code'],
            'pcgrcid' => $_REQUEST['pcgrcid'],
            'prog_curriculum_id' => $_GET['prog_curriculum_id'],
            'prog_curr_group_req_course_id' => $model['prog_curr_group_req_course_id']
            ]);
    }

    /**
     * Finds the ProgCurrGroupReqCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curr_group_req_course_id Prog Curr Group Req Course ID
     * @return ProgCurrGroupReqCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curr_group_req_course_id)
    {
        if (($model = ProgCurrGroupReqCourse::findOne(['prog_curr_group_req_course_id' => $prog_curr_group_req_course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
