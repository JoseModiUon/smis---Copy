<?php

namespace app\modules\studentRecords\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\SmWithdrawalApproval;
use app\models\search\SmWithdrawalApprovalSearch;
use app\models\SmWithdrawalRequest;

/**
 * SmWithdrawalApprovalController implements the CRUD actions for SmWithdrawalApproval model.
 */
class SmWithdrawalApprovalController extends Controller
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
     * download approvals
     */
    public function actionDownload()
    {
        $file=Yii::$app->request->get('file');
        $path=Yii::$app->request->get('document_url');
        $root=Yii::getAlias('@app') . DS . $path;
        if (file_exists($root)) {
            return Yii::$app->response->sendFile($root);
        } else {
            throw new \yii\web\NotFoundHttpException("{$file} is not found!");
        }
    }

    /**
     * Lists all SmWithdrawalApproval models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmWithdrawalApprovalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmWithdrawalApproval model.
     * @param int $withdrawal_approval_id  Withdrawal Approval ID
     * @param int $withdrawal_request_id  Withdrawal Request ID
     * @param int $approver_id Approver ID
     * @param string $approval_status Approval Status
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status)
    {
        return $this->render('view', [
            'model' => $this->findModel($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status),
        ]);
    }

    /**
     * Creates a new SmWithdrawalApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmWithdrawalApproval();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $m = SmWithdrawalRequest::findOne($model->withdrawal_request_id);
                $m->approval_status = $model->approval_status;
                $m->save();
                $student  = $model->getWithdrawalRequest()->one()->getStudent()->one();
                Yii::$app->getSession()->setFlash('success', "Approval Status for  {$student->surname} updated to: ".$model->approval_status);
                return $this->redirect(['sm-withdrawal-request/view', 'withdrawal_request_id' =>$model->withdrawal_request_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmWithdrawalApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $withdrawal_approval_id  Withdrawal Approval ID
     * @param int $withdrawal_request_id  Withdrawal Request ID
     * @param int $approver_id Approver ID
     * @param string $approval_status Approval Status
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status)
    {
        $model = $this->findModel($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'withdrawal_approval_id ' => $model->withdrawal_approval_id , 'withdrawal_request_id ' => $model->withdrawal_request_id , 'approver_id' => $model->approver_id, 'approval_status' => $model->approval_status]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmWithdrawalApproval model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $withdrawal_approval_id  Withdrawal Approval ID
     * @param int $withdrawal_request_id  Withdrawal Request ID
     * @param int $approver_id Approver ID
     * @param string $approval_status Approval Status
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status)
    {
        $this->findModel($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmWithdrawalApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $withdrawal_approval_id  Withdrawal Approval ID
     * @param int $withdrawal_request_id  Withdrawal Request ID
     * @param int $approver_id Approver ID
     * @param string $approval_status Approval Status
     * @return SmWithdrawalApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($withdrawal_approval_id , $withdrawal_request_id , $approver_id, $approval_status)
    {
        if (($model = SmWithdrawalApproval::findOne(['withdrawal_approval_id ' => $withdrawal_approval_id , 'withdrawal_request_id ' => $withdrawal_request_id , 'approver_id' => $approver_id, 'approval_status' => $approval_status])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
