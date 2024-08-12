<?php

namespace app\modules\examinationManagement\controllers;

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\models\search\CrProgCurrTimetableSearch;
use app\models\search\CrProgrammeCurrLectureTimetableSearch;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Crap4j;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrProgrammeCurrLectureTimetableController implements the CRUD actions for CrProgrammeCurrLectureTimetable model.
 */
class CrProgrammeCurrLectureTimetableController extends Controller
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
     * Lists all CrProgrammeCurrLectureTimetable models.
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
     * Displays a single CrProgrammeCurrLectureTimetable model.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($timetable_id)
    {

        $searchModel = new CrProgrammeCurrLectureTimetableSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, ['timetable_id' => $timetable_id]);

        return $this->render('timetables', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CrProgrammeCurrLectureTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CrProgrammeCurrLectureTimetable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $session = Yii::$app->session;
                return $this->redirect(['index', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CrProgrammeCurrLectureTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($lecture_timetable_id, $timetable_id)
    {
        $model = $this->findModel($lecture_timetable_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'timetable_id' => $timetable_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CrProgrammeCurrLectureTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($lecture_timetable_id)
    {
        $this->findModel($lecture_timetable_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrProgrammeCurrLectureTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $lecture_timetable_id Lecture Timetable ID
     * @return CrProgrammeCurrLectureTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($lecture_timetable_id)
    {
        if (($model = CrProgrammeCurrLectureTimetable::findOne(['lecture_timetable_id' => $lecture_timetable_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
