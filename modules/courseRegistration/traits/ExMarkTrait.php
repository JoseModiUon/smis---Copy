<?php

namespace app\modules\courseRegistration\traits;

use app\models\CrCourseRegistration;
use app\models\CrProgCurrTimetable;
use app\models\ExMarksheet;
use app\models\StudentProgrammeCurriculum;
use Yii;
use yii\db\ActiveQuery;

trait ExMarkTrait
{
    public function generateExStudentColumnsData()
    {
        $data = $this->request->post();
        if (array_key_exists('data', $data)) {
            return $this->handleColumns($data['data']);
        }
        $columns = [];
        foreach ($data as $reg_no => $marks) {
            $column = $this->getData(array_merge($marks, ['reg_no' => $reg_no]));
            $columns[] = $this->generateColumns(array_merge($marks, $column));
        }
        return $columns;
    }

    private function handleColumns($datax)
    {
        $columns = [];
        foreach ($datax as $data) {
            foreach ($data as $reg_no => $marks) {
                $column = $this->getData(array_merge($marks, ['reg_no' => $reg_no]));
                $columns[] = $this->generateColumns(array_merge($marks, $column));
            }
        }
        return $columns;
    }

    private function getData(array $marks)
    {
        ['timetable_id' => $timetable_id,  'marksheet_id' => $marksheet_id, 'reg_no' => $reg_no] = $marks;

        $finalMark = array_sum(array_filter($marks, fn ($key) => $key == 'course_work_mark' || $key == 'exam_mark', ARRAY_FILTER_USE_KEY));

        $mrksheet = CrProgCurrTimetable::findOne($timetable_id)->mrksheet_id;
        $course_reg_type = ExMarkSheet::find()
            ->select([
                'ex_marksheet.marksheet_id',
                'cr_course_reg_type.course_reg_type_code',
            ])
            ->innerJoinWith(['studentCourseReg' => function (ActiveQuery $q) {
                $q->innerJoinWith(['courseRegistrationType']);
            }])
            ->where(['ex_marksheet.marksheet_id' => $marksheet_id])
            ->asArray()->one();

        $grading = StudentProgrammeCurriculum::find()
            ->select([
                'sm_student_programme_curriculum.student_prog_curriculum_id',
                'grade',
                'org_academic_levels.academic_level_id',
            ])
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
            ->where(['>=', 'upper_bound', $finalMark])
            ->andWhere(['<=', 'lower_bound',  $finalMark])
            ->asArray()
            ->one();
        $items = explode("_", $mrksheet);

        $session = $items[0];
        $course_id = array_pop($items);
        $course_code = array_pop($items);
        $examType = $course_reg_type['course_reg_type_code'];

        return [
            'exam_type' => $examType,
            'academic_session' => $session,
            'course_id' => $course_id,
            'course_code' => $course_code,
            'final_mark' => $finalMark,
            'grade' => $grading['grade'],
            'reg_no' => str_replace("_", "/", $reg_no),
            'mrksheet_id' => $mrksheet,
            'level_of_study' => $grading['academic_level_id'],

        ];
    }

    private function generateColumns(array $data)
    {
        return [
            'course_registration_id' => $this->generateColumn('course_registration_id', $data),
            'progress_code' => $this->generateColumn('progress_code', $data),
            'course_id' => $data['course_code'] . '_' . $data['course_id'],
            'examtype_code' => $data['exam_type'],
            'final_mark' => $data['final_mark'],
            'grade' => $data['grade'],
            'mrksheet_id' => $data['mrksheet_id'],
            'course_work_mark' => $data['course_work_mark'],
            'course_mark' => $data['course_work_mark'],
            'level_of_study' => $data['level_of_study'],
            'exam_mark' => $data['exam_mark'],
            'marksheet_id' => $data['marksheet_id'],
            'userid' => Yii::$app->user->identity->username,

        ];
    }

    private function generateColumn(string $column, array $data): string
    {
        if ($column == 'course_registration_id') {
            return $data['reg_no'] . "-" . $data['academic_session'] . "-" . $data['course_code'] . "_" . $data['course_id'];
        }
        if ($column == 'progress_code') {
            return $data['reg_no'] . "-" . $data['academic_session'];
        }
    }
}
