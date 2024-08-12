<?php

/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-05-13 16:05:39 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-05-13 18:06:44
 * @Description: file:///home/user/Documents/smis/modules/feesManagement/controllers/FeeItemsController.php
 */


/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-05-13 16:05:39 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-05-13 18:06:44
 * @Description: file:///home/user/Documents/smis/modules/feesManagement/controllers/FeeItemsController.php
 */


namespace app\modules\feesManagement\controllers;

use app\models\FeeItems;
use app\models\search\FeeItemsSearch;
use app\modules\setup\utils\AutoSynchronize;
use app\traits\ControllerTrait;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * FeeItemsController implements the CRUD actions for FeeItems model.
 */
class FeeItemsController extends Controller
{
    /**
     * @inheritDoc
     */
    use ControllerTrait;

    use ControllerTrait;

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
     * Lists all FeeItems models.
     *
     * @return string
     */
    public function actionIndex()
    {
        try {
            $searchModel = new FeeItemsSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
        try {
            $searchModel = new FeeItemsSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'title' => 'Fee Items Setup'
            ]);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'title' => 'Fee Items Setup'
            ]);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Displays a single FeeItems model.
     * @param int $fee_code Fee Code
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($fee_code)
    {
        return $this->render('view', [
            'model' => $this->findModel($fee_code),
        ]);
    }

    /**
     * Creates a new FeeItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        // $spTransaction = Yii::$app->db2->beginTransaction();
        try {
            $model = new FeeItems();
            $post = $this->request->post();

            if ($post) {
                if ($model->load($post) && $model->save()) {
                    // $autoSync = new AutoSynchronize([$model]);
                    // $autoSync->save();
                    $transaction->commit();
                    // $spTransaction->commit();
                    return $this->redirect(['index']);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'title' => 'Create Fee Item',
                'model' => $model,
            ]);
        } catch (\Throwable $th) {
            $transaction->rollBack();
            // $spTransaction->rollBack();

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Updates an existing FeeItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $fee_code Fee Code
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fee_code)
    {
        try {
            $model = $this->findModel($fee_code);
        try {
            $model = $this->findModel($fee_code);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }

            return $this->render('update', [
                'title' => 'Update Fee Item',
                'model' => $model,
            ]);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
            return $this->render('update', [
                'title' => 'Update Fee Item',
                'model' => $model,
            ]);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Deletes an existing FeeItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $fee_code Fee Code
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($fee_code)
    {
        try {
            $this->findModel($fee_code)->delete();
        try {
            $this->findModel($fee_code)->delete();

            return $this->redirect(['index']);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
            return $this->redirect(['index']);
        } catch (\Throwable $th) {

            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Finds the FeeItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $fee_code Fee Code
     * @return FeeItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fee_code)
    {
        if (($model = FeeItems::findOne(['fee_code' => $fee_code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
