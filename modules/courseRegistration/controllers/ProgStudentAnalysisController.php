<?php

namespace app\modules\courseRegistration\controllers;

use app\models\CrCourseRegistration;
use app\models\CrProgCurrTimetable;
use app\models\OrgCourses;
use app\models\OrgProgrammeCurriculum;
use app\models\SmStudentProgrammeCurriculum;
use app\modules\courseRegistration\models\search\ProgStudentAnalysisSearch;
use app\modules\studentRegistration\models\AcademicSession;
use kartik\form\ActiveForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgStudentAnalysisController implements the CRUD actions for CrProgCurrTimetable model.
 */
class ProgStudentAnalysisController extends Controller
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

    public function getStudentCount($conditions){
        $studentCount = CrCourseRegistration::find()->select([
            'COUNT(DISTINCT cr_course_registration.registration_number) student_count',
            'org_courses.course_id',
            'org_courses.course_code',
            'org_semester_type.semester_type',
            'org_programme_curriculum.prog_curriculum_id',
            'org_academic_session.acad_session_id',
        ])
        ->innerJoinWith(['timetable' => function (ActiveQuery $t) {
            $t->innerJoinWith(['progCurriculumSemGroup' =>function (ActiveQuery $pcsg) {
                $pcsg->innerJoinWith(['progCurriculumSemester' =>function (ActiveQuery $pcs) {
                    $pcs->innerJoinWith(['orgSemesterType']);
                    $pcs->innerJoinWith(['progCurriculum' =>function (ActiveQuery $pc) {
                        $pc->innerJoinWith(['orgProgCurrCourses' =>function (ActiveQuery $opcc){
                            $opcc->innerJoinWith('course');
                        }]);
                    }]);
                    $pcs->innerJoinWith(['acadSessionSemester' =>function (ActiveQuery $ass) {
                        $ass->innerJoinWith('acadSession');
                    }]);
                }]);
            }]);
        }])
        ->groupBy(['org_courses.course_id', 'org_semester_type.semester_type', 'org_programme_curriculum.prog_curriculum_id', 'org_academic_session.acad_session_id'])
        ->asArray();

        foreach ($conditions as $condition) {
            $studentCount = $studentCount->andWhere($condition);
        }

        return $studentCount->all();
    }
    /**
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgStudentAnalysisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $sessions = AcademicSession::find()->select([
            'org_academic_session.acad_session_id',
            'org_academic_session.acad_session_name'
        ])->asArray()->all();

        $studentCount = CrCourseRegistration::find()->select([
            'COUNT(cr_course_registration.registration_number) AS student_count',
            'org_academic_session.acad_session_id',
            'org_semester_type.semester_type',
        ])->joinWith(['timetable' => function (ActiveQuery $t) {
            $t->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $pcsg) {
                $pcsg->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $pcs) {
                    $pcs->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                        $ass->innerJoinWith('acadSession');
                    }]);
                    $pcs->innerJoinWith('progCurriculum');
                    $pcs->innerJoinWith('orgSemesterType');
                }]);
            }]);
        }])
            ->groupBy(['org_academic_session.acad_session_id', 'org_semester_type.semester_type'])
            ->asArray();

            $teachingQuery = clone $studentCount;
            $teachingCount = $teachingQuery->where(['org_semester_type.semester_type' => 'TEACHING'])->all();
    
            $supplimentaryQuery = clone $studentCount;
            $supplementaryCount = $supplimentaryQuery->where(['org_semester_type.semester_type' => 'SUPPLEMENTARY'])->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $sessions,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sessions' => $sessions,
            'teachingCount' => $teachingCount,
            'supplementaryCount' => $supplementaryCount,
            'title' => 'Academic Student Analysis Report'
        ]);
    }

    /**
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionProgrammeAnalysis($acad_session_id)
    {
        $searchModel = new ProgStudentAnalysisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $programmes = OrgProgrammeCurriculum::find()->select([
            'org_programme_curriculum.prog_curriculum_desc',
            'org_programme_curriculum.prog_curriculum_id',
            'org_academic_session.acad_session_id',
        ])
            ->innerJoinWith(['orgProgCurrSemesters' => function (ActiveQuery $opcs) {
                $opcs->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                    $ass->innerJoinWith('acadSession');
                }]);
            }])
            ->where(['org_academic_session.acad_session_id' => $acad_session_id])
            ->asArray()->all();

        $studentCount = CrCourseRegistration::find()->select([
            'COUNT(cr_course_registration.registration_number) AS student_count',
            'org_programme_curriculum.prog_curriculum_id',
            'org_semester_type.semester_type',
        ])->joinWith(['timetable' => function (ActiveQuery $t) {
            $t->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $pcsg) {
                $pcsg->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $pcs) {
                    $pcs->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                        $ass->innerJoinWith('acadSession');
                    }]);
                    $pcs->innerJoinWith('progCurriculum');
                    $pcs->innerJoinWith('orgSemesterType');
                }]);
            }]);
        }])
            ->where(['org_academic_session.acad_session_id' => $acad_session_id])
            ->groupBy(['org_programme_curriculum.prog_curriculum_id', 'org_semester_type.semester_type'])
            ->asArray();


            $teachingQuery = clone $studentCount;
            $teachingCount = $teachingQuery->andWhere(['org_semester_type.semester_type' => 'TEACHING'])->all();
    
            $supplimentaryQuery = clone $studentCount;
            $supplementaryCount = $supplimentaryQuery->andWhere(['org_semester_type.semester_type' => 'SUPPLEMENTARY'])->all();


        $dataProvider = new ArrayDataProvider([
            'allModels' => $programmes,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        return $this->render('programme-analysis', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'acad_session_id' => $acad_session_id,
            'teachingCount' => $teachingCount,
            'supplementaryCount' => $supplementaryCount,
            'title' => 'Programme Student Analysis Report'
        ]);
    }

    /**
     * Lists all CrProgCurrTimetable models.
     *
     * @return string
     */
    public function actionCourseAnalysis($prog_curriculum_id)
    {
        $id = Yii::$app->request->get()['acad_session_id'];
        // dd($url);
        $searchModel = new ProgStudentAnalysisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // dd($dataProvider->getModels());

        function getCourseData($conditions)
        {
            $course_data = OrgCourses::find()->select([
                'org_programme_curriculum.prog_curriculum_id',
                'org_courses.course_code',
                'org_courses.course_id',
                'org_courses.course_name',
                'org_prog_curr_course.semester'
            ])
                ->innerJoinWith(['orgProgCurrCourses' => function (ActiveQuery $opcc) {
                    $opcc->innerJoinWith('progCurriculum');
                }])
                ->where(array_shift($conditions))
                ->asArray();

            foreach ($conditions as $condition) {
                $course_data = $course_data->andWhere($condition);
            }

            return $course_data->all();
        }
        $conditions = [
            ['org_programme_curriculum.prog_curriculum_id' => $prog_curriculum_id],
            ['org_prog_curr_course.semester' => '1'],
        ];

        $sem1Courses =  getCourseData($conditions);

        $conditions = [
            ['org_programme_curriculum.prog_curriculum_id' => $prog_curriculum_id],
            ['org_prog_curr_course.semester' => '2'],
        ];

        $sem2Courses = getCourseData($conditions);
        
        $studentCount = CrCourseRegistration::find()->select([
            'COUNT(cr_course_registration.registration_number) AS total_students',
            'org_courses.course_id',
            'org_semester_type.semester_type',
        ])->joinWith(['timetable' => function (ActiveQuery $t) {
            $t->innerJoinWith(['orgProgCurrCourse' => function (ActiveQuery $opcc) {
                $opcc->innerJoinWith('course');
            }]);
            $t->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $pcsg) {
                $pcsg->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $pcs) {
                    $pcs->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $ass) {
                        $ass->innerJoinWith('acadSession');
                    }]);
                    $pcs->innerJoinWith('progCurriculum');
                    $pcs->innerJoinWith('orgSemesterType');
                }]);
            }]);
        }])->groupBy([
            'org_courses.course_id', 'org_semester_type.semester_type', 'org_academic_session.acad_session_id','org_programme_curriculum.prog_curriculum_id'
        ])
            ->where([
                'org_academic_session.acad_session_id' => $id,
                'org_programme_curriculum.prog_curriculum_id' => $prog_curriculum_id
            ])
            ->asArray();

        $teachingQuery = clone $studentCount;
        $teachingCount = $teachingQuery->andWhere(['org_semester_type.semester_type' => 'TEACHING'])->all();

        $supplimentaryQuery = clone $studentCount;
        $supplementaryCount = $supplimentaryQuery->andWhere(['org_semester_type.semester_type' => 'SUPPLEMENTARY'])->all();



        $semesterOneData = new ArrayDataProvider([
            'allModels' => $sem1Courses,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $semesterTwoData = new ArrayDataProvider([
            'allModels' => $sem2Courses,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('course-analysis', [
            'searchModel' => $searchModel,
            'semesterOneData' => $semesterOneData,
            'semesterTwoData' => $semesterTwoData,
            'id' => $id,
            'teachingCount' => $teachingCount,
            'supplementaryCount' => $supplementaryCount,
            'title' => 'Course Student Analysis Report'
        ]);
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
