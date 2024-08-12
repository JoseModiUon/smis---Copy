<?php

namespace app\modules\feesManagement\controllers;

use app\models\PaymentMode;
use app\models\search\PaymentModeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentModeController implements the CRUD actions for PaymentMode model.
 */
class PaymentModeController extends Controller
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
     * Lists all PaymentMode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentModeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentMode model.
     * @param int $payment_mode_id Payment Mode ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($payment_mode_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($payment_mode_id),
        ]);
    }

    /**
     * Creates a new PaymentMode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PaymentMode();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // return $this->redirect(['view', 'payment_mode_id' => $model->payment_mode_id]);
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
     * Updates an existing PaymentMode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $payment_mode_id Payment Mode ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($payment_mode_id)
    {
        $model = $this->findModel($payment_mode_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'payment_mode_id' => $model->payment_mode_id]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PaymentMode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $payment_mode_id Payment Mode ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($payment_mode_id)
    {
        $this->findModel($payment_mode_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PaymentMode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $payment_mode_id Payment Mode ID
     * @return PaymentMode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($payment_mode_id)
    {
        if (($model = PaymentMode::findOne(['payment_mode_id' => $payment_mode_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
