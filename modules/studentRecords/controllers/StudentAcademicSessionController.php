<?php

namespace app\modules\studentRecords\controllers;

use app\modules\feesManagement\models\StudentProgrammeCurriculum;
use app\modules\studentRecords\models\search\StudentProgrammeCurriculumSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;

/**
 * StudentAcademicSessionController implements the CRUD actions for StudentProgrammeCurriculum model.
 */
class StudentAcademicSessionController extends Controller
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
     * Lists all StudentProgrammeCurriculum models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StudentProgrammeCurriculumSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentProgrammeCurriculum model.
     * @param int $student_prog_curriculum_id Student Prog Curriculum ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($student_prog_curriculum_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($student_prog_curriculum_id),
        ]);
    }

    /**
     * Creates a new StudentProgrammeCurriculum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StudentProgrammeCurriculum();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'student_prog_curriculum_id' => $model->student_prog_curriculum_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentProgrammeCurriculum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $student_prog_curriculum_id Student Prog Curriculum ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($student_prog_curriculum_id)
    {
        $model = $this->findModel($student_prog_curriculum_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'student_prog_curriculum_id' => $model->student_prog_curriculum_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    public function actionUpdateStatus()
    {
        $request = Yii::$app->request;
        $registration_number = $request->get('registration_number');
        $status_id = (integer) $request->get('status_id');

        $request = Yii::$app->request;
        $regno = $request->get('registration_number');    

        if($status_id == 1){
        Yii::$app->db->createCommand('UPDATE smis.sm_student_programme_curriculum SET status_id=:status_id WHERE registration_number=:registration_number')
        ->bindValue(':status_id', 2)
        ->bindValue(':registration_number', $registration_number)
        ->execute();
        }else if($status_id == 2){
            Yii::$app->db->createCommand('UPDATE smis.sm_student_programme_curriculum SET status_id=:status_id WHERE registration_number=:registration_number')
            ->bindValue(':status_id', 1)
            ->bindValue(':registration_number', $registration_number)
            ->execute();
        }
        return $this->redirect([
            '/student-records/student-academic-session/index',
            'StudentProgrammeCurriculumSearch[registration_number]' => $regno,
        ]);
    

    }

    /**
     * Deletes an existing StudentProgrammeCurriculum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $student_prog_curriculum_id Student Prog Curriculum ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($student_prog_curriculum_id)
    {
        $this->findModel($student_prog_curriculum_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StudentProgrammeCurriculum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $student_prog_curriculum_id Student Prog Curriculum ID
     * @return StudentProgrammeCurriculum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($student_prog_curriculum_id)
    {
        if (($model = StudentProgrammeCurriculum::findOne(['student_prog_curriculum_id' => $student_prog_curriculum_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
