<?php

namespace app\modules\setup\controllers;

use Yii;
use app\models\OrgUnitTypes;
use app\models\search\OrgUnitTypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgUnitTypesController implements the CRUD actions for OrgUnitTypes model.
 */
class OrgUnitTypesController extends Controller
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
     * Lists all OrgUnitTypes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgUnitTypesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgUnitTypes model.
     * @param int $unit_type_id Unit Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($unit_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($unit_type_id),
        ]);
    }

    /**
     * Creates a new OrgUnitTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgUnitTypes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'unit_type_id' => $model->unit_type_id]);
                Yii::$app->getSession()->setFlash('success', $model->unit_type_name.' Created!');
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
     * Updates an existing OrgUnitTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $unit_type_id Unit Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($unit_type_id)
    {
        $model = $this->findModel($unit_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
          //  return $this->redirect(['view', 'unit_type_id' => $model->unit_type_id]);
            Yii::$app->getSession()->setFlash('success', $model->unit_type_name.' Updated!');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgUnitTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $unit_type_id Unit Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($unit_type_id)
    {
        $this->findModel($unit_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgUnitTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $unit_type_id Unit Type ID
     * @return OrgUnitTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($unit_type_id)
    {
        if (($model = OrgUnitTypes::findOne(['unit_type_id' => $unit_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
