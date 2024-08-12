<?php

namespace app\modules\feesManagement\controllers;

use app\modules\feesManagement\models\BankAccounts;
use app\modules\feesManagement\models\search\BankAccountsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BankAccountsController implements the CRUD actions for BankAccounts model.
 */
class BankAccountsController extends Controller
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
     * Lists all BankAccounts models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BankAccountsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BankAccounts model.
     * @param int $brank_account_id Brank Account ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($brank_account_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($brank_account_id),
        ]);
    }

    /**
     * Creates a new BankAccounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BankAccounts();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'brank_account_id' => $model->brank_account_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BankAccounts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $brank_account_id Brank Account ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($brank_account_id)
    {
        $model = $this->findModel($brank_account_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'brank_account_id' => $model->brank_account_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BankAccounts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $brank_account_id Brank Account ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($brank_account_id)
    {
        $this->findModel($brank_account_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BankAccounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $brank_account_id Brank Account ID
     * @return BankAccounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($brank_account_id)
    {
        if (($model = BankAccounts::findOne(['brank_account_id' => $brank_account_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
