<?php

namespace app\modules\examManagement\controllers;

use yii\data\ArrayDataProvider;
use app\modules\examManagement\models\OrgProgCurrCourse;
use app\modules\examManagement\models\ProgrammeRequirement;
use app\modules\examManagement\models\ProgCurrGroupReqCourse;
use app\modules\examManagement\models\search\OrgProgCurrCourseSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * OrgProgCurrCourseController implements the CRUD actions for OrgProgCurrCourse model.
 */
class OrgProgCurrCourseController extends Controller
{
    /**
     * @inheritDoc
     */
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
     * Lists all OrgProgCurrCourse models.
     *
     * @return string
     */


    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();

        $searchModel = new OrgProgCurrCourseSearch();
        $dt = ProgrammeRequirement::GET_COURSES($get['prog_curriculum_id'], $get['prog_study_level']);

        // dd($dt);




        // $dataProvider = $searchModel->search($this->request->queryParams);
        //dd($dataProvider->getModels()->where);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 20,
            ],
            'allModels' => $dt]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataexport,
            'progCurrGroupReqId' => $get['prog_curr_group_requirement_id']
        ]);
    }
    /*
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();
        $searchModel = new OrgProgCurrCourseSearch();
          $dataProvider = $searchModel->search($this->request->queryParams);
        //dd($dataProvider->getModels()->where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'progCurrGroupReqId' => $get['prog_curr_group_requirement_id']
        ]);
    }*/

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

    public function actionAddCourseToRequirement()
    {
        try {
            $post = Yii::$app->request->post();

            $progCurrGroupReqId = $post['progCurrGroupReqId'];
            $url = $this->request->queryParams;


            foreach ($post['progCurrCourseIds'] as $progCurrCourseId) {
                $courses = $progCurrCourseId;
                $data_array = explode("-", $progCurrCourseId);
                $curr_course_id = $data_array[0];
                $credit_factor = $data_array[1];
                $model = new ProgCurrGroupReqCourse();
                $model['prog_curr_group_requirement_id'] = $progCurrGroupReqId;
                $model['prog_curriculum_course_id'] = $curr_course_id;
                $model['credit_factor'] = $credit_factor;
                $model->save();
                //                dd($this->redirect(['/exam-management/prog-curr-grp-req-course', $url]));


                // return $this->redirect(['/exam-management/prog-curr-grp-req-course/', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);

            }
            return $this->redirect(['/exam-management/prog-curr-grp-req-course', ...$url]);

        } catch (\Exception $ex) {

        }
    }



    /**
     * Creates a new OrgProgCurrCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrCourse();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
            return $this->redirect(['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);
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
}
