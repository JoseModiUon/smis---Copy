<?php

namespace app\controllers;
//namespace app\modules\courseRegistration\controllers;
use app\models\OrgSemesterType;
use app\models\search\OrgSemesterTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgSemesterTypeController implements the CRUD actions for OrgSemesterType model.
 */
class OrgSemesterTypeController extends Controller
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
     * Lists all OrgSemesterType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgSemesterTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgSemesterType model.
     * @param int $sem_type_id Sem Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sem_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sem_type_id),
        ]);
    }

    /**
     * Creates a new OrgSemesterType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgSemesterType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'sem_type_id' => $model->sem_type_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgSemesterType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sem_type_id Sem Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sem_type_id)
    {
        $model = $this->findModel($sem_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sem_type_id' => $model->sem_type_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgSemesterType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sem_type_id Sem Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sem_type_id)
    {
        $this->findModel($sem_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgSemesterType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sem_type_id Sem Type ID
     * @return OrgSemesterType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sem_type_id)
    {
        if (($model = OrgSemesterType::findOne(['sem_type_id' => $sem_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
