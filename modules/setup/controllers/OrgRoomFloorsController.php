<?php

namespace app\modules\setup\controllers;

use app\models\OrgRoomFloors;
use app\models\search\OrgRoomFloors as OrgRoomFloorsSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * OrgRoomFloorsController implements the CRUD actions for OrgRoomFloors model.
 */
class OrgRoomFloorsController extends Controller
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
     * Lists all OrgRoomFloors models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgRoomFloorsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgRoomFloors model.
     * @param int $floor_id Floor ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($floor_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($floor_id),
        ]);
    }

    /**
     * Creates a new OrgRoomFloors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgRoomFloors();
        // $transaction = Yii::$app->db->beginTransaction();
        // $spTransaction = Yii::$app->db2->beginTransaction();

        // try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
    
                    if (!$model->save()) {
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_DANGER,
                            'icon' => 'bi bi-x-circle-fill',
                            'message' => 'Could not add room floor. Please try again.',
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
                        'message' => "{$model->floor_name} added successfully",
                        'closeButton' => null,
                    ]);
    
                    return $this->redirect(['index']);
                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
            ]);
        // }catch (\Throwable $th) {
            // $transaction->rollBack();
            // $spTransaction->rollBack();
            // $message = $th->getMessage();
            // if (YII_ENV_DEV) {
                // $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            // }
            // throw new ServerErrorHttpException($message, 500);
        // }
    }

        
    

    /**
     * Updates an existing OrgRoomFloors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $floor_id Floor ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($floor_id)
    {
        $model = $this->findModel($floor_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Floor - {$model->floor_name} updated successfully",
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
     * Deletes an existing OrgRoomFloors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $floor_id Floor ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($floor_id)
    {
        $this->findModel($floor_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgRoomFloors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $floor_id Floor ID
     * @return OrgRoomFloors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($floor_id)
    {
        if (($model = OrgRoomFloors::findOne(['floor_id' => $floor_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
