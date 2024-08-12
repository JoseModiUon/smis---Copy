<?php

namespace app\modules\examinationManagement\controllers;

use app\models\ExMarksheet;
use app\models\search\ExMarksheetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExMarksheetController implements the CRUD actions for ExMarksheet model.
 */
class ExMarksheetController extends Controller
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
     * Lists all ExMarksheet models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExMarksheetSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExMarksheet model.
     * @param int $marksheet_id Marksheet ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($marksheet_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($marksheet_id),
        ]);
    }

    /**
     * Creates a new ExMarksheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExMarksheet();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'marksheet_id' => $model->marksheet_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExMarksheet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $marksheet_id Marksheet ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($marksheet_id)
    {
        $model = $this->findModel($marksheet_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'marksheet_id' => $model->marksheet_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExMarksheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $marksheet_id Marksheet ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($marksheet_id)
    {
        $this->findModel($marksheet_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExMarksheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $marksheet_id Marksheet ID
     * @return ExMarksheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($marksheet_id)
    {
        if (($model = ExMarksheet::findOne(['marksheet_id' => $marksheet_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
