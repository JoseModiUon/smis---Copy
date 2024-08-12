<?php

namespace app\modules\studentRecords\controllers;

use app\models\Banks;
use app\models\search\BankSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BanksController implements the CRUD actions for Banks model.
 */
class BanksController extends Controller
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
     * Lists all Banks models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BankSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banks model.
     * @param int $bank_id Bank ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bank_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($bank_id),
        ]);
    }

    /**
     * Creates a new Banks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Banks();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'bank_id' => $model->bank_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $bank_id Bank ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($bank_id)
    {
        $model = $this->findModel($bank_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'bank_id' => $model->bank_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $bank_id Bank ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bank_id)
    {
        $this->findModel($bank_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $bank_id Bank ID
     * @return Banks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bank_id)
    {
        if (($model = Banks::findOne(['bank_id' => $bank_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
