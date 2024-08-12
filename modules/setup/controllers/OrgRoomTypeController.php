<?php

namespace app\modules\setup\controllers;

use app\models\OrgRoomType;
use app\models\search\OrgTypeSearch;
use app\modules\setup\utils\AutoSynchronize;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\setup\utils\DuplicateChecker;
use kartik\growl\Growl;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * OrgTypeController implements the CRUD actions for OrgRoomType model.
 */
class OrgRoomTypeController extends Controller
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
     * Lists all OrgRoomType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgRoomType model.
     * @param int $room_type_id Room Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($room_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($room_type_id),
        ]);
    }

    /**
     * Creates a new OrgRoomType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgRoomType();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();


        try{
            if ($this->request->isPost) {

                $dpCheck = new DuplicateChecker();
                $dpCheck->insert(OrgRoomType::class, $this, [
                    ['room_type_name' => $this->request->post('OrgRoomType')['room_type_name']]

                ]);
                if ($models = $dpCheck->getObjectModel()) {
                    $autoSync = new AutoSynchronize([$models]);
                    $autoSync->save();
                }
                $transaction->commit();
                $spTransaction->commit();

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
     * Updates an existing OrgRoomType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $room_type_id Room Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($room_type_id)
    {
        $model = $this->findModel($room_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Room Type updated successfully",
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
     * Deletes an existing OrgRoomType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $room_type_id Room Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($room_type_id)
    {
        $this->findModel($room_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgRoomType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $room_type_id Room Type ID
     * @return OrgRoomType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($room_type_id)
    {
        if (($model = OrgRoomType::findOne(['room_type_id' => $room_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
