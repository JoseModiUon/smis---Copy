<?php

namespace app\modules\functionalSetup\controllers;

use app\models\OrgProgCurrUnit;
use app\models\search\OrgProgCurrUnitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgCurrUnitController implements the CRUD actions for OrgProgCurrUnit model.
 */
class OrgProgCurrUnitController extends Controller
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
     * Lists all OrgProgCurrUnit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrUnitSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrUnit model.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_unit_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_unit_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrUnit();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgCurrUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_unit_id)
    {
        $model = $this->findModel($prog_curriculum_unit_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrUnit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_unit_id)
    {
        $this->findModel($prog_curriculum_unit_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_unit_id Prog Curriculum Unit ID
     * @return OrgProgCurrUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_unit_id)
    {
        if (($model = OrgProgCurrUnit::findOne(['prog_curriculum_unit_id' => $prog_curriculum_unit_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
