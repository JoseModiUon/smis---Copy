<?php

namespace app\modules\setup\controllers;

use Yii;
use kartik\growl\Growl;
use yii\web\Controller;
use app\models\OrgBuilding;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\search\OrgBuildingSearch;
use app\modules\setup\utils\AutoSynchronize;
use yii\web\ServerErrorHttpException;

/**
 * OrgBuildingController implements the CRUD actions for OrgBuilding model.
 */
class OrgBuildingController extends Controller
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
     * Lists all OrgBuilding models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgBuildingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgBuilding model.
     * @param int $building_id Building ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($building_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($building_id),
        ]);
    }

    /**
     * Creates a new OrgBuilding model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgBuilding();
        // $transaction = Yii::$app->db->beginTransaction();
        // $spTransaction = Yii::$app->db2->beginTransaction();

        // try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    //check if the same building with same room name exists
                    $building = OrgBuilding::find()->where([
                        'building_code' => $model->building_code,
                        'building_name' => $model->building_name,
                        'study_center' => $model->study_center
                    ])->one();
                    if (!$building) {
                        if (!$model->save()) {
                            Yii::$app->getSession()->setFlash('', [
                                'type' => Growl::TYPE_DANGER,
                                'icon' => 'bi bi-x-circle-fill',
                                'message' => 'Could not add building. Please try again.',
                                'closeButton' => null,
                            ]);
                        }
                        // $autoSync = new AutoSynchronize([$model]);
                        // $autoSync->save();
    
                        // $transaction->commit();
                        // $spTransaction->commit();

                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_SUCCESS,
                            'icon' => 'bi bi-check-circle-fill',
                            'message' => "{$model->building_code} added successfully",
                            'closeButton' => null,
                        ]);
    
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_WARNING,
                            'icon' => 'bi bi-exclamation-circle-fill',
                            'message' => 'Duplicate record. Please provide unique building.',
                            'closeButton' => null,
                        ]);
                        return $this->redirect(['index']);
                    }
                }
            } else {
                $model->loadDefaultValues();
            }
        // }catch (\Throwable $th) {
            // $transaction->rollBack();
            // $spTransaction->rollBack();
            // $message = $th->getMessage();
            // if (YII_ENV_DEV) {
                // $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            // }
            // throw new ServerErrorHttpException($message, 500);
        // }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgBuilding model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $building_id Building ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($building_id)
    {
        $model = $this->findModel($building_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "{$model->building_code} has been updated successfully",
                    'closeButton' => null,
                ]);
                // $autoSync = new AutoSynchronize();
                // if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                // }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgBuilding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $building_id Building ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($building_id)
    {
        $this->findModel($building_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgBuilding model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $building_id Building ID
     * @return OrgBuilding the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($building_id)
    {
        if (($model = OrgBuilding::findOne(['building_id' => $building_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
