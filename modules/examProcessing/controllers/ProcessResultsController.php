<?php

namespace app\modules\examProcessing\controllers;

use app\models\OrgProgCurrCourse;
use app\models\OrgProgrammeCurriculum;
use app\modules\examProcessing\models\ExamParameters;
use app\modules\examProcessing\processor\ExamProcessor;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ProcessResultsController extends \app\controllers\BaseController
{
    /**
     * @return string
     * @throws Exception
     *
     * @deprecated
     */
    public function actionIndexOld(): string
    {
        $this->view->title = 'Exam results processing';

        $model = new ExamParameters();
        if ($model->load(Yii::$app->request->post())) {

            //load the programmeCurriculum
            $programmeCurriculum = OrgProgrammeCurriculum::findOne(['prog_id' => $model->programme_id]);
            //load the student details
            $passMark = $programmeCurriculum->pass_mark;
            $progDuration = $programmeCurriculum->duration;
            $awardRounding = $programmeCurriculum->award_rounding;
            $averagingType = $programmeCurriculum->average_type;

            $students = $model->fetchStudentProgress($model);


            $programmeCurriculumCourseQuery = OrgProgCurrCourse::find();
            $programmeCurriculumCourseQuery->where([
                'prog_curriculum_id' => $programmeCurriculum->prog_curriculum_id,
            ]);

            if ($model->academic_level > 0) {
                $programmeCurriculumCourseQuery->where([
                    'level_of_study' => $model->academic_level,
                ]);

            }

            $programmeCurriculumCourse = $programmeCurriculumCourseQuery->limit(3)->all();


            return Json::encode([
                'model' => $model,
                'programmeCurriculum' => $programmeCurriculum,
                'student' => $students,
                'programmeCurriculumCourse' => $programmeCurriculumCourse
            ]);

        }
        return $this->render('index', ['model' => $model]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Exam processing';

        $model = new ExamParameters();

        if ($model->load(Yii::$app->request->post())) {

            //load the programmeCurriculum
            $programmeCurriculum = OrgProgrammeCurriculum::findOne(['prog_id' => $model->programme_id]);
            //load the student details
            $passMark = $programmeCurriculum->pass_mark;
            $progDuration = $programmeCurriculum->duration;
            $awardRounding = $programmeCurriculum->award_rounding;
            $averagingType = $programmeCurriculum->average_type;

            $processor = new ExamProcessor(
                academicYearId: $model->academic_year,
                academicLevelId: $model->academic_level,
                progDuration: $progDuration,
                awardRounding: $awardRounding,
                averagingType: $averagingType,
                passMark: $passMark);

            $students = $model->fetchStudentProgress($model);

            //iterate over the students now
            $studentCount = 0;
            foreach ($students as $key => $student) {
                /**
                 * Array
                 * (
                 * [academic_progress_id] => 9
                 * [acad_session_id] => 81
                 * [academic_level_id] => 1
                 * [student_prog_curriculum_id] => 253
                 * [progress_status_id] => 2
                 * [current_status] => 1
                 * [student_id] => 1219
                 * [registration_number] => P15/246/2023
                 * [prog_curriculum_id] => 36
                 * [prog_id] => 16
                 * [prog_curriculum_desc] => BACHELOR OF SCIENCE IN  COMPUTER SCIENCE
                 * [duration] => 4
                 * [pass_mark] => 50
                 * [annual_semesters] => 2
                 * [max_units_per_semester] => 7
                 * [average_type] => 2
                 * [award_rounding] => TRUNCATE
                 * [grading_system_id] => 1
                 * )
                 */
                $studentCount++;
                $regNo = ArrayHelper::getValue($student, 'registration_number');
                //TODO Inquire what the student option is
                $studentOptionReq = $model->fetchProgLevelReq(
                    $programmeCurriculum->prog_curriculum_id,
                    $student['academic_level_id']
                );

                $recommendation = $processor->processStudentRecommendation(
                    $regNo,
                    $studentOptionReq,
                    $model->min_total_courses,
                    $model->max_total_courses,
                    $model->academic_year,
                    $programmeCurriculum->prog_curriculum_id,
                    $progDuration,
                    $awardRounding,
                    $averagingType
                );

//                return Json::encode([
//                    'levelReq' => $studentOptionReq,
//                    'recommendation' => $recommendation
//                ]);
            }


            return Json::encode([
                'feedback' => "$studentCount students processed",
                'model' => $model,
                'programmeCurriculum' => $programmeCurriculum,
                'student' => $students,
            ]);

        }
        return $this->render('index', ['model' => $model]);
    }
}
