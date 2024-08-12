<?php

namespace app\modules\setup\controllers;

use app\models\OrgRoomStatus;
use app\models\search\OrgRoomStatusSearch;
use app\modules\setup\utils\DuplicateChecker;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgRoomStatusController implements the CRUD actions for OrgRoomStatus model.
 */
class OrgRoomStatusController extends Controller
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
     * Lists all OrgRoomStatus models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgRoomStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgRoomStatus model.
     * @param int $room_status_id Room Status ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($room_status_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($room_status_id),
        ]);
    }

    /**
     * Creates a new OrgRoomStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgRoomStatus();

        if ($this->request->isPost) {
            (new DuplicateChecker())->insert(OrgRoomStatus::class, $this, [
                ['room_status_desc' => $this->request->post('OrgRoomStatus')['room_status_desc']]
            ]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgRoomStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $room_status_id Room Status ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($room_status_id)
    {
        $model = $this->findModel($room_status_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgRoomStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $room_status_id Room Status ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($room_status_id)
    {
        $this->findModel($room_status_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgRoomStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $room_status_id Room Status ID
     * @return OrgRoomStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($room_status_id)
    {
        if (($model = OrgRoomStatus::findOne(['room_status_id' => $room_status_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
