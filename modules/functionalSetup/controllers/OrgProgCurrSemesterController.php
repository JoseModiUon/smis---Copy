<?php

namespace app\modules\functionalSetup\controllers;

use app\models\OrgProgCurrSemester;
use app\models\search\OrgProgCurrSemesterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrgProgCurrSemesterController implements the CRUD actions for OrgProgCurrSemester model.
 */
class OrgProgCurrSemesterController extends Controller
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
     * Lists all OrgProgCurrSemester models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrgProgCurrSemesterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrgProgCurrSemester model.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prog_curriculum_semester_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prog_curriculum_semester_id),
        ]);
    }

    /**
     * Creates a new OrgProgCurrSemester model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new OrgProgCurrSemester();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prog_curriculum_semester_id' => $model->prog_curriculum_semester_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrgProgCurrSemester model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prog_curriculum_semester_id)
    {
        $model = $this->findModel($prog_curriculum_semester_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prog_curriculum_semester_id' => $model->prog_curriculum_semester_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrgProgCurrSemester model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prog_curriculum_semester_id)
    {
        $this->findModel($prog_curriculum_semester_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrgProgCurrSemester model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prog_curriculum_semester_id Prog Curriculum Semester ID
     * @return OrgProgCurrSemester the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prog_curriculum_semester_id)
    {
        if (($model = OrgProgCurrSemester::findOne(['prog_curriculum_semester_id' => $prog_curriculum_semester_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
