<?php

namespace app\modules\studentRecords\controllers;

use app\models\ReceiptSponsorFund;
use app\models\search\ReceiptSponsorFundSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReceiptSponsorFundController implements the CRUD actions for ReceiptSponsorFund model.
 */
class ReceiptSponsorFundController extends Controller
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
     * Lists all ReceiptSponsorFund models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (array_key_exists('sponsor_id', $this->request->queryParams)) {
            Yii::$app->session->set('sponsor_id', $this->request->queryParams['sponsor_id']);
        }
        $searchModel = new ReceiptSponsorFundSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReceiptSponsorFund model.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($receipt_sponsor_fund_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($receipt_sponsor_fund_id),
        ]);
    }

    /**
     * Displays a single ReceiptSponsorFund model.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUploadBeneficiaries($receipt_sponsor_fund_id)
    {
        return $this->render('upload-beneficiaries', [
            'model' => $this->findModel($receipt_sponsor_fund_id),
        ]);
    }

    /**
     * Displays a List of Beneficuaries model.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBeneficiaries($receipt_sponsor_fund_id)
    {
        return $this->render('beneficiaries', [
            'model' => $this->findModel($receipt_sponsor_fund_id),
        ]);
    }

    /**
     * Creates a new ReceiptSponsorFund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ReceiptSponsorFund();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'sponsor_id' => Yii::$app->session->get('sponsor_id')]);
            }
            dd($model->getErrors());
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReceiptSponsorFund model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($receipt_sponsor_fund_id)
    {
        $model = $this->findModel($receipt_sponsor_fund_id);
        $request = Yii::$app->request;

        $get = $request->get('sponsor_id');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect([
                'index',
                // 'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                'sponsor_id' => $get
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ReceiptSponsorFund model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($receipt_sponsor_fund_id)
    {
        $this->findModel($receipt_sponsor_fund_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReceiptSponsorFund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $receipt_sponsor_fund_id Receipt Sponsor Fund ID
     * @return ReceiptSponsorFund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($receipt_sponsor_fund_id)
    {
        if (($model = ReceiptSponsorFund::findOne(['receipt_sponsor_fund_id' => $receipt_sponsor_fund_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
