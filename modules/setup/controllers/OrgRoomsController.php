<?php

namespace app\modules\setup\controllers;

use Yii;
use kartik\growl\Growl;
use yii\web\Controller;
use app\models\OrgRooms;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\search\OrgRoomsSearch;
use app\modules\setup\utils\AutoSynchronize;
use yii\web\ServerErrorHttpException;

/**
 * OrgRoomsController implements the CRUD actions for OrgRooms model.
 */
class OrgRoomsController extends Controller
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
     * Lists all OrgRooms models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new OrgRoomsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgRooms model.
     * @param int $room_id Room ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($room_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($room_id),
        ]);
    }

    /**
     * Creates a new OrgRooms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgRooms();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();


        try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    //check if the same building with same room name exists
                    $room = OrgRooms::find()->where(['fk_building_id' => $model->fk_building_id])
                        ->andWhere(['ilike', 'room_name', $model->room_name . '%', false])
                        ->one();
                    if (!$room) {
                        if (!$model->save()) {
                            Yii::$app->getSession()->setFlash('', [
                                'type' => Growl::TYPE_DANGER,
                                'icon' => 'bi bi-x-circle-fill',
                                'message' => 'Could not add room. Please try again.',
                                'closeButton' => null,
                            ]);
                        }
                        $autoSync = new AutoSynchronize([$model]);
                        $autoSync->save();

                        $transaction->commit();
                        $spTransaction->commit();
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_SUCCESS,
                            'icon' => 'bi bi-check-circle-fill',
                            'message' => "{$model->room_code} added successfully",
                            'closeButton' => null,
                        ]);
    
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->getSession()->setFlash('', [
                            'type' => Growl::TYPE_WARNING,
                            'icon' => 'bi bi-exclamation-circle-fill',
                            'message' => 'Room name already in use for selected building. Please provide unique room name.',
                            'closeButton' => null,
                        ]);
                        return $this->redirect(['index']);
                    }
                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
            ]);

        }catch (\Throwable $th) {
            $transaction->rollBack();
            $spTransaction->rollBack();
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }


    /**
     * Updates an existing OrgRooms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $room_id Room ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($room_id)
    {
        $model = $this->findModel($room_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Room - {$model->room_code} updated successfully",
                    'closeButton' => null,
                ]);
                $autoSync = new AutoSynchronize();
                if ($autoSync->bulkSync(get_class($model))) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgRooms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $room_id Room ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($room_id)
    {
        $this->findModel($room_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgRooms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $room_id Room ID
     * @return OrgRooms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($room_id)
    {
        if (($model = OrgRooms::findOne(['room_id' => $room_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
