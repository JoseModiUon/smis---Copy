<?php

namespace app\modules\studentRecords\controllers;


use app\models\SmWithdrawalType;
use app\models\search\SmWithdrawalTypeSearch;
use app\modules\setup\utils\AutoSynchronize;
use kartik\growl\Growl;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * SmWithdrawalTypeController implements the CRUD actions for SmWithdrawalType model.
 */
class SmWithdrawalTypeController extends Controller
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
     * Lists all SmWithdrawalType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmWithdrawalTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmWithdrawalType model.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($withdrawal_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($withdrawal_type_id),
        ]);
    }

    /**
     * Creates a new SmWithdrawalType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmWithdrawalType();
        $transaction = Yii::$app->db->beginTransaction();
        $spTransaction = Yii::$app->db2->beginTransaction();

        try {
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    $autoSync = new AutoSynchronize([$model]);
                    $autoSync->save();

                    $transaction->commit();
                    $spTransaction->commit();

                    Yii::$app->getSession()->setFlash('', [
                        'type' => Growl::TYPE_SUCCESS,
                        'icon' => 'bi bi-check-circle-fill',
                        'message' => 'Withdrawal Type Created!',
                        'closeButton' => null,
                    ]);
                    return $this->redirect('index');

                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
            ]);

        } catch (\Throwable $th) {
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
     * Updates an existing SmWithdrawalType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($withdrawal_type_id)
    {
        $model = $this->findModel($withdrawal_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('', [
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'bi bi-check-circle-fill',
                    'message' => "Withdrawal Type updated successfully",
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
     * Deletes an existing SmWithdrawalType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($withdrawal_type_id)
    {
        $this->findModel($withdrawal_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmWithdrawalType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $withdrawal_type_id Withdrawal Type ID
     * @return SmWithdrawalType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($withdrawal_type_id)
    {
        if (($model = SmWithdrawalType::findOne(['withdrawal_type_id' => $withdrawal_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
