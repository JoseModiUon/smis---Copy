<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgUnit;
use app\models\search\OrgUnitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgUnitController implements the CRUD actions for OrgUnit model.
 */
class OrgUnitController extends Controller
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
     * Lists all OrgUnit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgUnitSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgUnit model.
     * @param int $unit_id Unit ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($unit_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($unit_id),
        ]);
    }

    /**
     * Creates a new OrgUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgUnit();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
               // return $this->redirect(['view', 'unit_id' => $model->unit_id]);
                Yii::$app->getSession()->setFlash('success', $model->unit_name.' Created!');
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
     * Updates an existing OrgUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $unit_id Unit ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($unit_id)
    {
        $model = $this->findModel($unit_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'unit_id' => $model->unit_id]);
            Yii::$app->getSession()->setFlash('success', $model->unit_name.' Updated!');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgUnit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $unit_id Unit ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($unit_id)
    {
        $this->findModel($unit_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $unit_id Unit ID
     * @return OrgUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($unit_id)
    {
        if (($model = OrgUnit::findOne(['unit_id' => $unit_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
