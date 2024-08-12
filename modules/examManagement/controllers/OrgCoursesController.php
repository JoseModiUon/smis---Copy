<?php

namespace app\modules\examManagement\controllers;

use Yii;
use app\models\OrgCourses;
use app\models\search\OrgCoursesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgCoursesController implements the CRUD actions for OrgCourses model.
 */
class OrgCoursesController extends Controller
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
     * Lists all OrgCourses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgCoursesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgCourses model.
     * @param int $course_id Course ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_id),
        ]);
    }

    /**
     * Creates a new OrgCourses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgCourses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', $model->course_name.' Created!');
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'course_id' => $model->course_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgCourses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $course_id Course ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($course_id)
    {
        $model = $this->findModel($course_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->course_name.' Updated!');
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'course_id' => $model->course_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgCourses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $course_id Course ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($course_id)
    {
        $this->findModel($course_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgCourses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_id Course ID
     * @return OrgCourses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_id)
    {
        if (($model = OrgCourses::findOne(['course_id' => $course_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
