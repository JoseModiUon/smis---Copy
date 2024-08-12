<?php

namespace app\modules\examManagement\controllers;

use app\modules\examManagement\models\search\ConsolidatedMarksheetSearch;
use Yii;
use Exception;
use Mpdf\Mpdf;
use Dompdf\Dompdf;
use app\models\Student;
use yii\db\ActiveQuery;
use yii\web\Controller;
use app\models\Employee;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\models\ExStudentCourses;
use yii\data\ActiveDataProvider;
use app\models\CrProgCurrTimetable;
use app\models\StudentProgrammeCurriculum;
use app\models\ExMarksheet as ModelsExMarksheet;
use app\modules\courseRegistration\models\ExMarkSheet;
use app\modules\courseRegistration\traits\ExMarkTrait;
use app\modules\courseRegistration\models\CrCourseRegistration;
use app\modules\courseRegistration\models\search\ClassListSearch;
use app\models\CrCourseRegistration as ModelsCrCourseRegistration;
use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;
use app\modules\examManagement\models\search\FinalTranscriptSearch;
use app\modules\courseRegistration\models\search\ExamListViewSearch;
use app\modules\courseRegistration\models\search\ClassListViewSearch;
use app\modules\courseRegistration\models\search\MarksListViewSearch;
use app\modules\examManagement\models\search\FinalViewTranscriptSearch;
use app\modules\examManagement\models\search\ProvisionalTranscriptSearch;
use app\modules\examManagement\models\search\ProvisionalViewTranscriptSearch;
use app\modules\examManagement\pdf\ConsolidatedMarksheet;
use app\modules\examManagement\models\search\ClassListSearch as ClassListSearchExam;
use app\modules\examManagement\models\search\ClassListStudentSearch;
use app\modules\examManagement\models\search\SenateReportsSearch;
use yii\helpers\FileHelper;

class ReportsController extends Controller
{
    use CrProgCurrTimetableTrait;
    use ExMarkTrait;
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


    public function actionSaveTranscriptFull()
    {

        try {

            $path = Yii::getAlias('@app') . '/temp/transcripts';

            if (!is_dir($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
            }
            // $stylesheet = file_get_contents(Yii::getAlias('@app') . '/web/css/bootstrap3.min.css');
            $mpdf = new Mpdf(['tempDir' => $path]);
            // $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($this->renderPartial('provisional-full-transcript', [
                'gradingData' => $this->getGradingData(),
                'studentOrgData' => $this->findStudentOrgData(),
                'mpdf' => $mpdf
            ]), 2);

            $output = base64_encode($mpdf->Output('', 'S'));
            return $this->asJson([
                'success' => true,
                'output' => $output,
            ]);
        } catch (Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }

    public function actionSaveTranscriptLogoFull()
    {
        // dd($this->getGradingData());
        // return $this->render('provisional-transcript', [
        //     'gradingData' => $this->getGradingData(),
        //     'studentOrgData' => $this->findStudentOrgData()
        // ]);

        try {

            // $stylesheet = file_get_contents(Yii::getAlias('@app') . '/web/css/bootstrap3.min.css');
            $path = Yii::getAlias('@app') . '/temp/transcripts';

            if (!is_dir($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
            }
            $mpdf = new Mpdf(['tempDir' => $path]);

            $mpdf->WriteHTML($this->renderPartial('provisional-full-transcript-logo', [
                'gradingData' => $this->getGradingData(),
                'studentOrgData' => $this->findStudentOrgData(),
                'mpdf' => $mpdf
            ]), 2);

            $output = base64_encode($mpdf->Output('', 'S'));
            return $this->asJson([
                'success' => true,
                'output' => $output,
            ]);
        } catch (Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }



    public function actionSaveTranscript()
    {
        // return $this->render('provisional-transcript', [
        //     'gradingData' => $this->getGradingData(),
        //     'studentOrgData' => $this->findStudentOrgData()
        // ]);

        try {

            // $stylesheet = file_get_contents(Yii::getAlias('@app') . '/web/css/bootstrap3.min.css');
            $path = Yii::getAlias('@app') . '/temp/transcripts';
            if (!is_dir($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
            }
            $mpdf = new Mpdf(['tempDir' => $path]);
            // $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($this->renderPartial('provisional-transcript', [
                'gradingData' => $this->getGradingData(),
                'studentOrgData' => $this->findStudentOrgData()
            ]), 2);


            $output = base64_encode($mpdf->Output('', 'S'));
            return $this->asJson([
                'success' => true,
                'output' => $output,
            ]);
        } catch (Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }

    public function actionSaveConsolidatedMarksheet()
    {

        $pdf = new ConsolidatedMarksheet([
            'data' => $this->request->post('data'),
            'requestData' => $this->request->post('requestData'),
        ]);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->AddPage();
        $pdf->outputControl();

        return $this->asJson([
            'success' => true,
            'output' => base64_encode($pdf->Output('S')),
        ]);
    }

    public function actionSaveFinalTranscript()
    {


        try {
            // $stylesheet = file_get_contents(Yii::getAlias('@app') . '/web/css/bootstrap3.min.css');

            $path = Yii::getAlias('@app') . '/temp/transcripts';

            if (!is_dir($path)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
            }
            $mpdf = new Mpdf(['tempDir' => $path]);
            // $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($this->renderPartial('final-transcript', [
                'gradingData' => $this->getGradingData(),
                'studentOrgData' => $this->findStudentOrgData()
            ]), 2);
            $output = base64_encode($mpdf->Output('', 'S'));
            return $this->asJson([
                'success' => true,
                'output' => $output,
            ]);
        } catch (Exception $e) {
            return $this->asJson([
                'success' => false, 'message' => 'could not generate file.'
            ]);
        }
    }

    private function getStudent()
    {
        $student_id =  $this->request->queryParams['ProvisionalTranscriptSearch']['student_id'];
        return Student::find()
            ->select([
                'sm_student.student_id',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student.student_number',
                'sm_student.registration_date',
                'org_programmes.prog_full_name',
                'org_programme_curriculum.prog_curriculum_id',
                'org_unit.unit_code',
                'org_prog_curr_unit.prog_curriculum_unit_id',
            ])
            ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $stu) {
                $stu->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                    $pr->innerJoinWith(['orgProgCurrCourses']);
                    $pr->innerJoinWith(['prog']);
                    $pr->innerJoinWith(['orgProgCurrUnits' => function (ActiveQuery $currUnit) {
                        $currUnit->innerJoinWith(['orgUnitHistory' => function (ActiveQuery $uh) {
                            $uh->innerJoinWith(['orgUnit']);
                        }]);
                    }]);
                }]);
            }])
            ->where(['sm_student.student_id' => $student_id])->asArray()->one();
    }
    private function findStudentOrgData(): array
    {
        $student_id =  $this->request->post('student_id');
        return Student::find()
            ->select([
                'sm_student.student_id',
                'timetable_id',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student.student_number',
                'sm_student.registration_date',
                'org_programmes.prog_full_name',
                'org_programme_curriculum.prog_curriculum_id',
                'org_unit.unit_code',
            ])
            ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $stu) {
                $stu->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                    $pr->innerJoinWith(['orgProgCurrCourses' => function (ActiveQuery $org) {
                        $org->innerJoinWith(['timetable' => function (ActiveQuery $tt) {
                            $tt->innerJoinWith(['exStudentCourses']);
                        }]);
                    }]);
                    $pr->innerJoinWith(['prog']);
                    $pr->innerJoinWith(['orgProgCurrUnits' => function (ActiveQuery $currUnit) {
                        $currUnit->innerJoinWith(['orgUnitHistory' => function (ActiveQuery $uh) {
                            $uh->innerJoinWith(['orgUnit']);
                        }]);
                    }]);
                }]);
            }])
            ->where(['sm_student.student_id' => $student_id])
            ->andWhere("course_registration_id LIKE CONCAT('%', sm_student_programme_curriculum.registration_number, '%')")->asArray()->one();
    }
    private function getGradingData(): array
    {
        $postedData = $this->request->post();
        $data = ExStudentCourses::find()
            ->select([
                'ex_student_courses.course_registration_id',
                'ex_student_courses.student_courses_id',
                'round(ex_student_courses.final_mark, 0) as final_mark',
                'ex_student_courses.grade',
                'org_courses.course_code',
                'org_courses.course_name',
                'org_courses.academic_hours',
                // 'org_prog_level.programme_level_id',
                // 'org_prog_level.programme_level_name',
                'org_academic_session.acad_session_name',
                'org_prog_curr_course.level_of_study',
                'org_prog_type.prog_type_code',
                'cr_course_category.category_name'


            ])
            ->distinct()
            ->innerJoinWith(['timetable' => function (ActiveQuery $q) {
                $q->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $semGroup) {
                    $semGroup->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $progcm) {
                        $progcm->innerJoinWith(['acadSessionSemester' => function (ActiveQuery $acadss) {
                            $acadss->innerJoinWith(['acadSession']);
                        }]);
                    }]);
                    $semGroup->innerJoinWith(['programmeLevel']);
                }]);
                $q->innerJoinWith(['orgProgCurrCourse' => function (ActiveQuery $curr) {
                    $curr->joinWith(['semesterCode', 'academicLevels']);
                    $curr->joinWith(['course' => function (ActiveQuery $course) {
                        $course->joinWith(['category']);
                    }]);
                    $curr->joinWith(['progCurriculum' => function (ActiveQuery $pr) {
                        $pr->joinWith(['prog' => function (ActiveQuery $typ) {
                            $typ->joinWith(['progType']);
                        }]);
                    }]);
                }]);
            }])
            ->orderBy([
                'org_prog_curr_course.level_of_study' => SORT_ASC,
                'org_courses.course_code' => SORT_ASC
            ])
            ->where(['like', 'course_registration_id', $postedData['reg_no'] . '%', false])->asArray()->all();

        $orgData = [];
        foreach ($data as $index => $row) {
            if ($index == 0) {
                $orgData[(int)$row['level_of_study']][] = $row;
            } else {
                $records = array_filter($orgData, function ($item, $level) use ($row) {
                    if ((int)$row['level_of_study'] == $level) {
                        return in_array($row['student_courses_id'], array_column($item, 'student_courses_id'));
                    }
                }, ARRAY_FILTER_USE_BOTH);
                if (empty($records)) {
                    $orgData[(int)$row['level_of_study']][] = $row;
                }
            }
        }
        ksort($orgData, SORT_NUMERIC);
        return $orgData;
    }

    public function actionFinalTranscript()
    {
        $searchModel = new FinalTranscriptSearch();

        if (!empty(Yii::$app->request->get())) {
            // dd($this->request->queryParams);
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('final-transcript-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProvisionalTranscript()
    {
        $searchModel = new ProvisionalTranscriptSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('provisional-transcript-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionFullProvisionalTranscript()
    {
        $searchModel = new ProvisionalTranscriptSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('provisional-transcript-full', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSenateReports()
    {
        $searchModel = new SenateReportsSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('senate-reports', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionViewProvisionalTranscript()
    {

        $searchModel = new ProvisionalViewTranscriptSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // dd($dataProvider->query->all());
        return $this->render('provisional-transcript-view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewFullProvisionalTranscript()
    {

        $searchModel = new ProvisionalViewTranscriptSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // dd($dataProvider->query->all());
        return $this->render('provisional-full-transcript-view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewConsolidatedMarksheet()
    {
        $searchModel = new ConsolidatedMarksheetSearch();

        if (!empty($this->request->queryParams)) {
            $dataProvider = $searchModel->search($this->request->queryParams['ConsolidatedMarksheetSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => ExStudentCourses::find()->where(['student_courses_id' => 0])
            ]);
        }

        return $this->render('consolidated-marksheet', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $dataProvider->getModels(),
        ]);
    }
    public function actionViewFinalTranscript()
    {

        $searchModel = new FinalViewTranscriptSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        // dd($dataProvider->query->all());
        return $this->render('final-transcript-view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExamList()
    {

        $searchModel = new ClassListSearch();

        if (!empty(Yii::$app->request->get())) {
            // dd($this->request->queryParams);
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('exam-list-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewExamList()
    {
        $searchModel = new ExamListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('exam-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionViewBlankMarksheet()
    {
        $searchModel = new ExamListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('blank-marksheet-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionMarksEntry()
    {

        $searchModel = new ClassListSearchExam();

        if (!empty(Yii::$app->request->get())) {
            // dd($this->request->queryParams);
            $dataProvider = $searchModel->search($this->request->queryParams['ClassListSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('marks-entry-course-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStudentMarksEntry()
    {
        $searchModel = new ClassListStudentSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('marks-entry-student-course-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMarksStudentEntry()
    {
        return $this->redirect(['/courseRegistration/reports/view-student-marks-entry-list']);
    }


    public function actionBlankMarksheet()
    {

        $searchModel = new ClassListSearch();

        if (!empty(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search($this->request->queryParams['ClassListSearch']);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => CrProgCurrTimetable::find()->where(['timetable_id' => 0])
            ]);
        }

        return $this->render('blank-exam-list-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionViewMarksEntryList()
    {

        $searchModel = new MarksListViewSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('marks-entry-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * assign values to model dynamically
     * @param Object $model
     * @param array $params
     * @return void
     */
    private function assign(Object $model, array $params): void
    {
        foreach ($params as $key => $value) {
            if (in_array($key, $model->attributes())) {
                $model->{$key} = $value;
            }
        }
    }

    public function actionMarkGrade()
    {
        $columns = $this->generateExStudentColumnsData();
        foreach ($columns as $mark) {
            $marksheet = ModelsExMarksheet::findOne($mark['marksheet_id']);
            $exStudentCourses = ExStudentCourses::find()->where(['course_registration_id' => $mark['course_registration_id']])->one();
            if (!$exStudentCourses) {
                $exStudentCourses = new ExStudentCourses();
            }

            $this->assign($marksheet, $mark);
            $this->assign($exStudentCourses, $mark);

            // dd($exStudentCourses->save());
            $ok = $marksheet->save() && $exStudentCourses->save();
            $ok = $marksheet->save();
            if (!$ok) {
                break;
            }
        }
        if (!$ok) {
            return $this->asJson(['success' => false, 'message' => 'updating marks failed.']);
        }
        return $this->asJson(['success' => true, 'message' => 'marks updated successfully!']);
    }


    public function actionFindGrade()
    {
        [
            'reg_no' => $reg_no,
            'final_mark' => $final_mark
        ] = $this->request->post();

        $grading = StudentProgrammeCurriculum::find()
            ->select('grade')
            ->innerJoinWith(['student' => function (ActiveQuery $stu) use ($reg_no) {
                $stu->where(['registration_number' => str_replace("_", "/", $reg_no)]);
            }])
            ->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                $pr->innerJoinWith(['gradingSystem' => function (ActiveQuery $prog) {
                    $prog->innerJoinWith(['gradingDetail']);
                }]);
            }])
            ->innerJoinWith(['smAcademicProgresses' => function (ActiveQuery $acad) {
                $acad->innerJoinwith(['academicLevel']);
            }])
            ->where(['>=', 'upper_bound', $final_mark])
            ->andWhere(['<=', 'lower_bound',  $final_mark])
            ->asArray()
            ->one();

        if ($grading) {
            return $this->asJson(['success' => true, 'grade' => $grading['grade']]);
        }
        return $this->asJson(['success' => false, 'grade' => '']);
    }


    private function sync()
    {
        $data = ExMarkSheet::find()->asArray()->all();


        foreach ($data as $row) {
            $course = new \app\models\ExMarksheet();
            $this->assign($course, $row);
            $ok = $course->save();
        }
    }
}
