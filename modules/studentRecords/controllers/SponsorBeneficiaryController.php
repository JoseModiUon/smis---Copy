<?php

namespace app\modules\studentRecords\controllers;

use app\modules\studentRecords\models\SponsorBeneficiary;
use app\modules\studentRecords\models\SponsorBeneficiarySearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;

/**
 * SponsorBeneficiaryController implements the CRUD actions for SponsorBeneficiary model.
 */
class SponsorBeneficiaryController extends Controller
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
     * Lists all SponsorBeneficiary models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SponsorBeneficiarySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SponsorBeneficiary model.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sponsor_beneficiary_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sponsor_beneficiary_id),
        ]);
    }


    /**
     * Displays a single SponsorBeneficiary model.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewCust($sponsor_beneficiary_id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sponsor Beneficiary",
                'content' => $this->renderAjax('view-cust', [
                    'model' => $this->findModel($sponsor_beneficiary_id),
                ]),
                'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                    Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update-cust', 'sponsor_beneficiary_id' => $sponsor_beneficiary_id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view-cust', [
                'model' => $this->findModel($sponsor_beneficiary_id),
            ]);
        }
    }



    /**
     * Creates a new SponsorBeneficiary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SponsorBeneficiary();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new SponsorBeneficiary model through receipt.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreateReceipt()
    {

        $request = Yii::$app->request;

        $receipt = $request->get('receipt_sponsor_fund_id');
        $sponsor = $request->get('sponsor_id');

        $model = new SponsorBeneficiary();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('yii2-ajaxcrud', ''),
                    'content' => $this->renderAjax('create-receipt', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceClose' => true, 'forceReload' => '#crud-datatable-pjax'
                ];
            } else {
                return [
                    'title' => Yii::t('yii2-ajaxcrud', 'Update') . " Sponsor Beneficiaries #",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect([
                        'receipt-sponsor-fund/beneficiaries', 'receipt_sponsor_fund_id' => $receipt,
                        'sponsor_id' => $sponsor
                    ]);
                }
            } else {
                $model->loadDefaultValues();
            }
        }
    }


    /**
     * Updates an existing SponsorBeneficiary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sponsor_beneficiary_id)
    {
        $model = $this->findModel($sponsor_beneficiary_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing SponsorBeneficiary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionOptimize()
    {
        $request = Yii::$app->request;
        $quest = Yii::$app->request;
        $receipt = $request->get('receipt_sponsor_fund_id');
        $sponsor = $request->get('sponsor_id');
        $average = $request->get('average');

        // dd($average);

        Yii::$app->db->createCommand("UPDATE smis.fss_sponsor_beneficiary SET amount=" . $average . " WHERE receipt_sponsor_fund_id=" . $receipt)->execute();


        if ($quest->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['/student-records/receipt-sponsor-fund/beneficiaries', 'receipt_sponsor_fund_id' => $receipt, 'sponsor_id' => $sponsor, 'forceReload' => '#crud-datatable-pjax']);
        }
    }




    /**
     * Updates an existing SponsorBeneficiary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateCust($sponsor_beneficiary_id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($sponsor_beneficiary_id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('yii2-ajaxcrud', 'Update Sponsor Beneficiary'),
                    'content' => $this->renderAjax('update-cust', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "View Sponsor Beneficiary",
                    'content' => $this->renderAjax('view-cust', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update-cust', 'sponsor_beneficiary_id' => $sponsor_beneficiary_id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('yii2-ajaxcrud', 'Update') . " Banks #" . $sponsor_beneficiary_id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }





    /**
     * Deletes an existing SponsorBeneficiary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sponsor_beneficiary_id)
    {
        $this->findModel($sponsor_beneficiary_id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing SponsorBeneficiary model from beneficiary by sponsor list.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteByReceipt($sponsor_beneficiary_id)
    {

        $request = Yii::$app->request;
        $this->findModel($sponsor_beneficiary_id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }


    /**
     * Finds the SponsorBeneficiary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return SponsorBeneficiary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sponsor_beneficiary_id)
    {
        if (($model = SponsorBeneficiary::findOne(['sponsor_beneficiary_id' => $sponsor_beneficiary_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * Finds the SponsorBeneficiary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sponsor_beneficiary_id Sponsor Beneficiary ID
     * @return SponsorBeneficiary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($receipt_sponsor_fund_id)
    {
        if (($model = SponsorBeneficiary::findAll(['sponsor_beneficiary_id' => $receipt_sponsor_fund_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
