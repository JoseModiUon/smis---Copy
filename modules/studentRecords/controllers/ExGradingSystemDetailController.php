<?php

namespace app\modules\studentRecords\controllers;

use app\models\ExGradingSystemDetail;
use app\models\search\ExGradingSystemDetailSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExGradingSystemDetailController implements the CRUD actions for ExGradingSystemDetail model.
 */
class ExGradingSystemDetailController extends Controller
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
     * Lists all ExGradingSystemDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExGradingSystemDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExGradingSystemDetail model.
     * @param int $grading_system_detail_id Grading System Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($grading_system_detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($grading_system_detail_id),
        ]);
    }

    /**
     * Creates a new ExGradingSystemDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExGradingSystemDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'grading_system_id' => Yii::$app->session->get('grading_system_id')]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExGradingSystemDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $grading_system_detail_id Grading System Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($grading_system_detail_id)
    {
        $model = $this->findModel($grading_system_detail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'grading_system_id' => Yii::$app->session->get('grading_system_id')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExGradingSystemDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $grading_system_detail_id Grading System Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($grading_system_detail_id)
    {
        $this->findModel($grading_system_detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExGradingSystemDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $grading_system_detail_id Grading System Detail ID
     * @return ExGradingSystemDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($grading_system_detail_id)
    {
        if (($model = ExGradingSystemDetail::findOne(['grading_system_detail_id' => $grading_system_detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
