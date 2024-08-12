<?php

namespace app\modules\feesManagement\controllers;

use app\modules\feesManagement\models\BillingType;
use app\modules\feesManagement\models\search\BillingTypeSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillTypeController implements the CRUD actions for BillingType model.
 */
class BillTypeController extends Controller
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
     * Lists all BillingType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BillingTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BillingType model.
     * @param int $billing_type_id Billing Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($billing_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($billing_type_id),
        ]);
    }

    /**
     * Creates a new BillingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BillingType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['programme-curriculum/index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BillingType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $billing_type_id Billing Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($billing_type_id)
    {
        $model = $this->findModel($billing_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BillingType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $billing_type_id Billing Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($billing_type_id)
    {
        $this->findModel($billing_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BillingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $billing_type_id Billing Type ID
     * @return BillingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($billing_type_id)
    {
        if (($model = BillingType::findOne(['billing_type_id' => $billing_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
