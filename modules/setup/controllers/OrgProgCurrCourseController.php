<?php

namespace app\modules\setup\controllers;
use Yii;
use app\models\OrgProgCurrCourse;
use app\models\search\OrgProgCurrCourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success','Programme Curriculum Course Created!');
                return $this->redirect(['index']);
              //  return $this->redirect(['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);
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
            Yii::$app->getSession()->setFlash('success','Programme Curriculum Course Updated!');
            return $this->redirect(['index']);
           // return $this->redirect(['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);
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
