<?php

namespace app\modules\examManagement\controllers;

use Yii;
use app\models\OrgProgrammes;
use app\models\search\OrgProgrammesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgrammesController implements the CRUD actions for OrgProgrammes model.
 */
class OrgProgrammesController extends Controller
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
     * Lists all OrgProgrammes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgrammesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgrammes model.
     * @param int $prog_id Prog ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_id),
        ]);
    }

    /**
     * Creates a new OrgProgrammes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgrammes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', $model->prog_short_name.' Created!');
                return $this->redirect(['index']);
               // return $this->redirect(['view', 'prog_id' => $model->prog_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgrammes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_id Prog ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_id)
    {
        $model = $this->findModel($prog_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', $model->prog_short_name.' Updated!');
            return $this->redirect(['index']);

            //  return $this->redirect(['view', 'prog_id' => $model->prog_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgrammes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_id Prog ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_id)
    {
        $this->findModel($prog_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgrammes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_id Prog ID
     * @return OrgProgrammes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_id)
    {
        if (($model = OrgProgrammes::findOne(['prog_id' => $prog_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
