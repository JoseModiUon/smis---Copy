<?php

namespace app\modules\studentRegistration\controllers;

use app\modules\studentRegistration\models\AcademicSession;
use app\modules\studentRegistration\models\AcademicSessionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AcademicSessionController implements the CRUD actions for AcademicSession model.
 */
class AcademicSessionController extends Controller
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
     * Lists all AcademicSession models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AcademicSessionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AcademicSession model.
     * @param int $acad_session_id Acad Session ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($acad_session_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($acad_session_id),
        ]);
    }

    /**
     * Creates a new AcademicSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AcademicSession();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'acad_session_id' => $model->acad_session_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AcademicSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $acad_session_id Acad Session ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($acad_session_id)
    {
        $model = $this->findModel($acad_session_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'acad_session_id' => $model->acad_session_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AcademicSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $acad_session_id Acad Session ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acad_session_id)
    {
        $this->findModel($acad_session_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AcademicSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $acad_session_id Acad Session ID
     * @return AcademicSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($acad_session_id)
    {
        if (($model = AcademicSession::findOne(['acad_session_id' => $acad_session_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
