<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\ProgCurrCourseGroup;
use app\modules\examManagement\models\search\ProgCurrCourseGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgCurrCourseGrpController implements the CRUD actions for ProgCurrCourseGroup model.
 */
class ProgCurrCourseGrpController extends Controller
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
     * Lists all ProgCurrCourseGroup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgCurrCourseGroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgCurrCourseGroup model.
     * @param int $course_group_id Course Group ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_group_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_group_id),
        ]);
    }

    /**
     * Creates a new ProgCurrCourseGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrCourseGroup();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
                //                return $this->redirect(['view', 'course_group_id' => $model->course_group_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgCurrCourseGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $course_group_id Course Group ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($course_group_id)
    {
        $model = $this->findModel($course_group_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
            //            return $this->redirect(['view', 'course_group_id' => $model->course_group_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgCurrCourseGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $course_group_id Course Group ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($course_group_id)
    {
        $this->findModel($course_group_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProgCurrCourseGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_group_id Course Group ID
     * @return ProgCurrCourseGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_group_id)
    {
        if (($model = ProgCurrCourseGroup::findOne(['course_group_id' => $course_group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
