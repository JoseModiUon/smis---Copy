<?php

namespace app\modules\studentRecords\controllers;

use Yii;
use app\models\AdmittedStudent;
use app\models\OrgAcademicLevels;
use app\models\OrgAcademicSession;
use app\models\OrgCountry;
use app\models\OrgProgrammes;
use app\models\OrgUnit;
use app\models\search\AdmittedStudentSearch;
use app\models\search\StudentSearch;
use app\models\SmStudent;
use app\models\Student;
use app\modules\studentRecords\models\REPORTS;
use Exception;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use  kartik\mpdf\Pdf;
use yii\db\ActiveQuery;
use yii\web\ServerErrorHttpException;

/**
 * ReportsController implements the actions for Student Reports.
 */
class ReportsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ],
        );
    }

    /**
     * Reports default page
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Student vs Sponsors List
     *
     * @param int $id
     * @return string
     */
    public function actionStudentsPerSponsor(int $id = 0): string
    {
        if ($id === '') {
            $id = 0;
        }
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['sponsor' => $id]);
        $dataProvider->pagination = false;
        return $this->render('students-per-sponsor', [
            'id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * StudentNationality Stats Report
     *
     * @return string
     */
    public function actionStudentNationalityStats(): string
    {
        $data = OrgCountry::find()->alias("CN")->select(["CN.nationality", "COUNT(*) student_count"])
            ->innerJoinWith("students")->groupBy("CN.nationality")->asArray()->all();

        if (empty($data)) {
            \Yii::$app->getSession()->setFlash('danger', [
                'type' => 'warning',
                'message' => 'No Data found!',
                'title' => 'Note Report',
            ]);
        } else {
            $columns = array_keys($data[0]);
            array_splice($columns, 2);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => $columns
            ],
        ]);
        return $this->render('student-nationality-stats', [
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    /**
     * AdmittedStudent List Analysis
     *
     * @param string $intake
     * @return string
     */
    public function actionNominalAdmissionsAnalysis(string $intake = ''): string
    {
        if (empty($intake)) $intake = 0;
        $columns = [];
        $md = AdmittedStudent::find()->alias("AS")->select(["AS.admission_status", "COUNT(*) student_count"])
            ->groupBy("AS.admission_status");
        //        exit;
        if ($intake !== 0) {
            $md->andWhere(['intake_code' => $intake]);
        }
        $data = $md->asArray()->all();


        if (empty($data)) {
            \Yii::$app->getSession()->setFlash('danger', [
                'type' => 'warning',
                'message' => 'No Data found!',
                'title' => 'Analysis Report',
            ]);
        } else {
            $columns = array_keys($data[0]);
            array_splice($columns, 2);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => $columns
            ],
        ]);
        return $this->render('nominal-admissions-analysis', [
            'intake' => $intake,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    /**
     * Lists all AdmittedStudent models.
     *
     * @return string
     */
    public function actionAdmittedList(): string
    {
        $searchModel = new AdmittedStudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('admitted-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionStudentListPerSession()
    {
        $acad_session_id = Yii::$app->request->get('acad_session_id');
        $prog_code = Yii::$app->request->get('prog_code');



        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        }
        if (empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        }

        $dt = REPORTS::GET_STUDENTS_PER_SESSION($acad_session_id, $prog_code);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'student-list-per-session',
            [
                'dataexport' => $dataexport,
                'acad_session_id' => $acad_session_id,
                'prog_code' => $prog_code,
            ]
        );
    }


    public function actionForeignStudentsList()
    {
        $acad_session_id = Yii::$app->request->get('acad_session_id');
        //$prog_code=Yii::$app->request->post('prog_code');



        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        }
        /* if(empty(Yii::$app->request->post('prog_code'))) {
            $prog_code = 0;
        }*/

        $dt = REPORTS::GET_FOREIGN_STUDENTS_LIST($acad_session_id);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'foreign-students-list',
            [
                'dataexport' => $dataexport,
                'acad_session_id' => $acad_session_id,
                // 'prog_code'=>$prog_code,
            ]
        );
    }

    public function actionStudentStatistics()
    {
        try {
            $model = new OrgAcademicSession();
            $student = [];
            $maleStudent = [];
            $femaleStudent = [];
            $programmes = [];
            $units = [];
            $levels = [];

            $request = Yii::$app->request;
            if ($request->isGet) {
                $formData = $request->get('OrgAcademicSession');
                if ($formData !== null && isset($formData['acad_session_id'])) {
                    $acad_session_id = $formData['acad_session_id'];
                    $student = SmStudent::find()->select([
                        'COUNT(sm_student.student_number) as total_students',
                        'sm_student.gender',
                        'org_programmes.prog_short_name',
                        'org_academic_levels.academic_level',
                        'org_academic_session.acad_session_id'
                    ])->innerJoinWith(['smStudentProgrammeCurriculums' => function (ActiveQuery $sspc) {
                        $sspc->innerJoinWith(['smAcademicProgresses' => function (ActiveQuery $sap) {
                            $sap->innerJoinWith(['academicLevel', 'acadSession']);
                        }]);
                        $sspc->innerJoinWith(['progCurriculum' => function (ActiveQuery $pc) {
                            $pc->innerJoinWith('prog');
                        }]);
                    }])
                        ->where(['org_academic_session.acad_session_id' => $acad_session_id])
                        ->groupBy(['org_programmes.prog_short_name', 'org_academic_levels.academic_level', 'sm_student.gender', 'org_academic_session.acad_session_id'])
                        ->asArray();


                    $maleQuery = clone $student;
                    $maleStudent = $maleQuery->andWhere(['gender' => 'M'])->all();

                    $femaleQuery = clone $student;
                    $femaleStudent = $femaleQuery->andWhere(['gender' => 'F'])->all();

                    $programmes = OrgProgrammes::find()->select(['org_programmes.prog_short_name'])->asArray()->all();

                    $levels = OrgAcademicLevels::find()->select(['org_academic_levels.academic_level'])->asArray()->all();
                    $units = OrgUnit::find()->select(["unit_id", "unit_name", "org_programmes.prog_short_name"])
                        ->joinWith(['orgUnitHistories' => function (ActiveQuery $ouh) {
                            $ouh->joinWith(['orgProgCurrUnits' => function (ActiveQuery $opcu) {
                                $opcu->joinWith(['progCurriculum' => function (ActiveQuery $pc) {
                                    $pc->joinWith(['prog']);
                                }]);
                            }]);
                        }])
                        ->asArray()->all();
                }
            }

            return $this->render('student-statistics', [
                'title' => 'Overall Student Statistics',
                'model' => $model,
                'maleStudent' => $maleStudent,
                'femaleStudent' => $femaleStudent,
                'programmes' => $programmes,
                'units' => $units,
                'levels' => $levels
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionStudentStatsPerSession()
    {

        if (empty(Yii::$app->request->post('prog_code'))) {
            $prog_code = 0;
        }

        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        } else {
            $acad_session_id = Yii::$app->request->get('acad_session_id');
        }
        if (empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }


        if ($prog_code == 0 || $acad_session_id == 0) {

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()
            ]);
            return $this->render(
                'student-stats-per-session',
                [
                    'dataexport' => $dataexport,
                    'acad_session_id' => $acad_session_id,
                    'prog_code' => $prog_code,
                ]
            );
        }

        $dt = REPORTS::GET_STUDENTS_PER_SESSION_STATS($acad_session_id, $prog_code);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'student-stats-per-session',
            [
                'dataexport' => $dataexport,
                'acad_session_id' => $acad_session_id,
                'prog_code' => $prog_code,
                'dt' => $dt,
            ]
        );
    }
    public function actionStudentStats()
    {

        if (empty(Yii::$app->request->get('unit_code'))) {
            $unit_code = 0;
        } else {
            $unit_code = Yii::$app->request->get('unit_code');
        }

        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        } else {
            $acad_session_id = Yii::$app->request->get('acad_session_id');
        }
        /* if(empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
		   }
		   else{
			   $prog_code=Yii::$app->request->get('prog_code');
		   }*/


        if ($unit_code == 0 || $acad_session_id == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()
            ]);
            return $this->render(
                'student-stats',
                [
                    'dataexport' => $dataexport,
                    'acad_session_id' => $acad_session_id,
                    // 'prog_code'=>$prog_code,	 
                    'unit_code' => $unit_code,
                ]
            );
        }

        $dt = REPORTS::GET_STUDENT_STATS($acad_session_id, $unit_code);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'student-stats',
            [
                'dataexport' => $dataexport,
                'unit_code' => $unit_code,
                'acad_session_id' => $acad_session_id,
                // 'prog_code'=>$prog_code,
                'dt' => $dt,
            ]
        );
    }




    public function actionStudentStatsPerSessionPdf($acad_session_id, $prog_code, $acad_session)
    {
        $today = date('d-M-y');
        $dt = REPORTS::GET_STUDENTS_PER_SESSION_STATS($acad_session_id, $prog_code);
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial(
            '_student-stats-per-session-pdf',
            [
                'acad_session_id' => $acad_session_id,
                'prog_code' => $prog_code,
                'dt' => $dt,
            ]
        );

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'National Defence University'],
            // call mPDF methods on the fly
            'filename' => 'Student Statistics Per Programme ' . $prog_code . ' ' . $acad_session,
            'methods' => [

                //'SetHeader'=>['Generated on '.$today ], 
                'SetFooter' => ['|Page {PAGENO}| Generated on:' . $today . ''],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }






    public function actionNominalRollPerClass()
    {


        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        } else {
            $acad_session_id = Yii::$app->request->get('acad_session_id');
        }
        if (empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }
        if (empty(Yii::$app->request->get('academic_level_id'))) {
            $acad_level = 0;
        } else {

            $acad_level = Yii::$app->request->get('academic_level_id');
        }

        if ($prog_code == 0 || $acad_session_id == 0 || $acad_level == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()
            ]);
            return $this->render(
                'nominal-roll-per-class',
                [
                    'dataexport' => $dataexport,
                    'acad_session_id' => $acad_session_id,
                    'prog_code' => $prog_code,
                    'acad_level' => $acad_level,
                ]
            );
        }

        $dt = REPORTS::GET_STUDENTS_PER_CLASS($acad_session_id, $prog_code, $acad_level);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'nominal-roll-per-class',
            [
                'dataexport' => $dataexport,
                'acad_session_id' => $acad_session_id,
                'prog_code' => $prog_code,
                'acad_level' => $acad_level,
            ]
        );
    }
    public function actionNominalRollPerUnit()
    {

        if (empty(Yii::$app->request->get('acad_session_id'))) {
            $acad_session_id = 0;
        } else {
            $acad_session_id = Yii::$app->request->get('acad_session_id');
        }
        if (empty(Yii::$app->request->get('unit_code'))) {
            $unit_code = 0;
        } else {
            $unit_code = Yii::$app->request->get('unit_code');
        }
        if (empty(Yii::$app->request->get('academic_level_id'))) {
            $acad_level = 0;
        } else {

            $acad_level = Yii::$app->request->get('academic_level_id');
        }

        if ($unit_code == 0 || $acad_session_id == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()
            ]);
            return $this->render(
                'nominal-roll-per-unit',
                [
                    'dataexport' => $dataexport,
                    'acad_session_id' => $acad_session_id,
                    'unit_code' => $unit_code,
                    'acad_level' => $acad_level,
                ]
            );
        }





        $dt = REPORTS::GET_STUDENTS_PER_UNIT($acad_session_id, $unit_code, $acad_level);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'nominal-roll-per-unit',
            [
                'dataexport' => $dataexport,
                'acad_session_id' => $acad_session_id,
                'unit_code' => $unit_code,
                'acad_level' => $acad_level,
            ]
        );
    }

    public function actionStudentsInSession()
    {

        if (empty(Yii::$app->request->get('prog_code'))) {
            $prog_code = 0;
        } else {
            $prog_code = Yii::$app->request->get('prog_code');
        }
        if (empty(Yii::$app->request->get('academic_level_id'))) {
            $acad_level = 0;
        } else {

            $acad_level = Yii::$app->request->get('academic_level_id');
        }

        if ($prog_code == 0) {

            //$acad_level = 0;
            //$dataexport=[];

            $dataexport =   new ArrayDataProvider([
                'pagination' => [
                    'pageSize' => 200,
                ],
                'allModels' => array()
            ]);
            return $this->render(
                'students-in-session',
                [
                    'dataexport' => $dataexport,

                    'prog_code' => $prog_code,
                    'acad_level' => $acad_level,
                ]
            );
        }





        $dt = REPORTS::GET_STUDENTS_IN_SESSION($prog_code, $acad_level);

        $dataexport =   new ArrayDataProvider([
            'pagination' => [
                'pageSize' => 200,
            ],
            'allModels' => $dt
        ]);

        return $this->render(
            'students-in-session',
            [
                'dataexport' => $dataexport,
                'prog_code' => $prog_code,
                'acad_level' => $acad_level,
            ]
        );
    }
}
