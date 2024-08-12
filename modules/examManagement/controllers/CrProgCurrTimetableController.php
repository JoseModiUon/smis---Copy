<?php

namespace app\modules\examinationManagement\controllers;

use app\models\CrProgCurrTimetable;
use app\models\search\CrProgCurrTimetableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * CrProgCurrTimetableController implements the CRUD actions for CrProgCurrTimetable model.
 */
class CrProgCurrTimetableController extends Controller
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CrProgCurrTimetableSearch();
        if (null !== Yii::$app->request->get('CrProgCurrTimetableSearch')) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CrProgCurrTimetable model.
     * @param int $timetable_id Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($timetable_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($timetable_id),
        ]);
    }

    /**
     * Creates a new CrProgCurrTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CrProgCurrTimetable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', "Timetable created");
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CrProgCurrTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $timetable_id Timetable ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($timetable_id)
    {
        $model = $this->findModel($timetable_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', "Timetable created");
            $session = Yii::$app->session;
            return $this->redirect(['index', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CrProgCurrTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $timetable_id Timetable ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($timetable_id)
    {
        $this->findModel($timetable_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrProgCurrTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $timetable_id Timetable ID
     * @return CrProgCurrTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($timetable_id)
    {
        if (($model = CrProgCurrTimetable::findOne(['timetable_id' => $timetable_id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
