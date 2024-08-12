<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgUnitHead;
use app\models\search\OrgUnitHeadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgUnitHeadController implements the CRUD actions for OrgUnitHead model.
 */
class OrgUnitHeadController extends Controller
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
     * Lists all OrgUnitHead models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgUnitHeadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgUnitHead model.
     * @param int $unit_head_id Unit Head ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($unit_head_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($unit_head_id),
        ]);
    }

    /**
     * Creates a new OrgUnitHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgUnitHead();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
               // return $this->redirect(['view', 'unit_head_id' => $model->unit_head_id]);
                Yii::$app->getSession()->setFlash('success', $model->unit_head_name.' Created!');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgUnitHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $unit_head_id Unit Head ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($unit_head_id)
    {
        $model = $this->findModel($unit_head_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'unit_head_id' => $model->unit_head_id]);
            Yii::$app->getSession()->setFlash('success', $model->unit_head_name.' Updated!');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgUnitHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $unit_head_id Unit Head ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($unit_head_id)
    {
        $this->findModel($unit_head_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgUnitHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $unit_head_id Unit Head ID
     * @return OrgUnitHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($unit_head_id)
    {
        if (($model = OrgUnitHead::findOne(['unit_head_id' => $unit_head_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
