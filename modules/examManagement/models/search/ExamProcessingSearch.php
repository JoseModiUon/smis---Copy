<?php

namespace app\modules\examManagement\models\search;

use app\models\CrProgCurrTimetable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrSemesterGroup;
use yii\db\ActiveQuery;
use yii\db\Query;
use Yii;

class ExamProcessingSearch extends CrProgCurrTimetable
{
    public $curriculum;
    public $studyGroup;

    public $programmeLevel;
    public $prog_curriculum_id;
    public $progCurrCourse;
    public $progCurrSemGroup;
    public $examMode;
    public $examVenue;
    public $acad_session_id;
    public $study_group_id;
    public $study_centre_id;
    public $semester_type_id;
    public $semester_code;
    public $programme_level_id;
    public $academic_level_id;

    public $min_courses_taken; # minimum total courses taken to process
    public $max_courses_taken; #maximum total courses taken to process
    public $from_reg_no;#From registration number to process
    public $to_reg_no;#To registration number to process
    public $trace_processing;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['prog_curriculum_id', 'acad_session_id', 'study_centre_id', 'study_group_id', 'semester_type_id'
            , 'semester_code', 'academic_level_id'], 'required'],
          [[
            'exam_date', 'progCurrCourse', 'progCurrSemGroup',
            'examMode', 'examVenue', 'programme_level_id',
            'semester_code', 'academic_level_id',
            'semester_type_id', 'programmeLevel', 'curriculum', 'studyGroup', 'min_courses_taken'
          ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $filters = $params['ExamProcessingSearch'];
        //print_r($filters);
        // exit;
        $acadeSess = \app\modules\studentRegistration\models\AcademicSession::findOne($filters['acad_session_id']);

        $year = $acadeSess->acad_session_name;

        $progCurr = \app\modules\studentRegistration\models\ProgrammeCurriculum::findOne($filters['prog_curriculum_id']);
        $prog = \app\modules\studentRegistration\models\Programme::findOne($progCurr->prog_id);

        $progCode = $prog->prog_code;
        $acadLevel = \app\modules\studentRegistration\models\AcademicLevel::findOne($filters['academic_level_id']);
        $level = $acadLevel->academic_level;

        $partialMarksheetId = $year . '_' . $progCode . '_' . $level . '_' . $filters['semester_code'] . '_' . $filters['study_group_id'];


    }#end function

    public function getStudentsToProcess($params)
    {
        $students = array();
        if (isset($params)) {
            $filters = $params['ExamProcessingSearch'];
            $SQL = 'SELECT
              sm_student_programme_curriculum.registration_number,
              sm_student_programme_curriculum.prog_curriculum_id,
              MAX ( org_prog_curr_semester_group.programme_level ) programme_level,
              org_study_centre_group.study_group_id
            FROM
              smis.sm_academic_progress
              INNER JOIN smis.sm_student_programme_curriculum ON sm_student_programme_curriculum.student_prog_curriculum_id = sm_academic_progress.student_prog_curriculum_id
              INNER JOIN smis.org_programme_curriculum ON org_programme_curriculum.prog_curriculum_id = sm_student_programme_curriculum.prog_curriculum_id
              INNER JOIN smis.org_prog_curr_semester ON org_prog_curr_semester.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
              INNER JOIN smis.org_prog_curr_semester_group ON org_prog_curr_semester_group.prog_curriculum_semester_id = org_prog_curr_semester.prog_curriculum_semester_id
              INNER JOIN smis.org_study_centre_group ON org_study_centre_group.study_centre_group_id = org_prog_curr_semester_group.study_centre_group_id
            WHERE
                sm_student_programme_curriculum.prog_curriculum_id=:prog_curriculum_id
              AND sm_academic_progress.acad_session_id =:acad_session_id
              AND sm_academic_progress.academic_level_id = :academic_level_id
              AND org_study_centre_group.study_group_id =:study_group_id
            GROUP BY
              sm_student_programme_curriculum.registration_number,
              sm_student_programme_curriculum.prog_curriculum_id,
              org_study_centre_group.study_group_id';
            $command = Yii::$app->db->createCommand($SQL);
            $command->bindValues([':prog_curriculum_id' => $filters['prog_curriculum_id'], ':acad_session_id' => $filters['acad_session_id'], ':academic_level_id' => $filters['academic_level_id'], ':study_group_id' => $filters['study_group_id']]);
            $students = $command->queryAll();


        }
        return $students;
    }#end function

    public function getStudentCourses($registrationNumber)
    {
        if (isset($registrationNumber)) {
            $rows = (new \yii\db\Query())
              ->select(['cr_course_registration.registration_number',
                'ex_marksheet.marksheet_id',
                'ex_marksheet.final_mark',
                'cr_course_reg_type.course_reg_type_code',
                'cr_course_reg_type.course_reg_entry_type',
                'cr_course_reg_status.course_reg_status_name',
                'cr_prog_curr_timetable.prog_curriculum_course_id',
                'org_prog_curr_course.course_id',
                'org_prog_curr_course.level_of_study'
              ])
              ->from('smis.cr_course_registration')
              ->innerJoin('smis.ex_marksheet', 'cr_course_registration.student_course_reg_id = ex_marksheet.student_course_reg_id')
              ->innerJoin('smis.cr_course_reg_type', 'cr_course_registration.course_registration_type_id = cr_course_reg_type.course_reg_type_id')
              ->innerJoin('smis.cr_course_reg_status', 'cr_course_registration.course_reg_status_id = cr_course_reg_status.course_reg_status_id')
              ->innerJoin('smis.cr_prog_curr_timetable', 'cr_course_registration.timetable_id = cr_prog_curr_timetable.timetable_id')
              ->innerJoin('smis.org_prog_curr_course', 'org_prog_curr_course.prog_curriculum_course_id = cr_prog_curr_timetable.prog_curriculum_course_id')
              ->where(['cr_course_registration.registration_number' => $registrationNumber])
              ->orderBy(['org_prog_curr_course.level_of_study' => SORT_ASC, 'ex_marksheet.final_mark' => SORT_DESC])
              ->createCommand()
              ->queryAll();
            // die($rows);
            return $rows ?? null;
        }
    }

    /*
     * get programme curriculum
     */
    public function getProgrammeCurriculum($params)
    {
        $progCurrLevelRequirements = array();
        if (isset($params)) {
            $filters = $params['ExamProcessingSearch'];
            $progCurrID = $filters['prog_curriculum_id'];
            $rows = (new \yii\db\Query())
              ->select(['org_programme_curriculum.prog_curriculum_id',
                'org_programme_curriculum.prog_curriculum_desc',
                'org_programme_curriculum.duration',
                'org_programme_curriculum.pass_mark',
                'average_type',
                'org_programme_curriculum.award_rounding',
                'org_programme_curriculum.grading_system_id',
                'org_programmes.prog_id',
                'org_programmes.prog_code',
                'org_programmes.prog_short_name',
                'org_programmes.prog_full_name'])
              ->from('smis.org_programme_curriculum')
              ->innerJoin('smis.org_programmes', 'org_programme_curriculum.prog_id = org_programmes.prog_id')
              ->where(['org_programme_curriculum.prog_curriculum_id' => $progCurrID])
              ->orderBy(['prog_curriculum_id' => SORT_ASC])
              ->createCommand()
              ->queryOne();
            return $rows ?? null;
        }
    }#end function

    /*
     * get programme curriculum level rquirements
     */
    public function getProgCurrLevelReq($params)
    {
        if (isset($params)) {
            $filters = $params['ExamProcessingSearch'];
            $progCurrID = $filters['prog_curriculum_id'];
            if (isset($progCurrID)) {

                $rows = (new \yii\db\Query())
                  ->select(['prog_curr_level_req_id',
                    'prog_study_level',
                    'min_courses_taken',
                    'pass_type',
                    'min_pass_courses',
                    'gpa_choice',
                    'gpa_courses',
                    'gpa_weight',
                    'pass_result',
                    'pass_recommendation',
                    'fail_type',
                    'fail_result',
                    'fail_recommendation',
                    'incomplete_result',
                    'incomplete_recommendation'])
                  ->from('smis.prog_curr_level_requirement')
                  ->where(['prog_curriculum_id' => $progCurrID])
                  ->orderBy(['prog_study_level' => SORT_ASC])
                  ->createCommand()
                  ->queryAll();

                return $rows ?? null;
            }
        }
    }#end function

    /**
     * get programme curriculum grading system
     */
    public function getProgramCurriculumGradingSystem($params)
    {
        if (isset($params)) {
            $filters = $params['ExamProcessingSearch'];
            $progCurrID = $filters['prog_curriculum_id'];
            $rows = (new \yii\db\Query())
              ->select(['ex_grading_system.grading_system_id',
                'ex_grading_system.grading_system_name',
                'ex_grading_system_detail.lower_bound',
                'ex_grading_system_detail.upper_bound',
                'ex_grading_system_detail.grade',
                'ex_grading_system_detail.grade_point',
                'ex_grading_system_detail.is_active',
                'ex_grading_system_detail.legend',
                'ex_grading_system_detail.extlegend',
                'ex_grading_system_detail.recomm_id',
                'org_programme_curriculum.prog_curriculum_id'])
              ->from('smis.ex_grading_system')
              ->innerJoin('smis.ex_grading_system_detail', 'ex_grading_system.grading_system_id = ex_grading_system_detail.grading_system_id')
              ->innerJoin('smis.org_programme_curriculum', 'ex_grading_system.grading_system_id = org_programme_curriculum.grading_system_id')
              ->where(['org_programme_curriculum.prog_curriculum_id' => $progCurrID])
              ->where(['ex_grading_system.status' => 'ACTIVE'])
              ->where(['ex_grading_system_detail.is_active' => 'ACTIVE'])
              ->orderBy(['ex_grading_system_detail.lower_bound' => SORT_DESC])
              ->createCommand()
              ->queryAll();
            return $rows ?? null;
        }
    }

    /**
     * get  programme level group requirements
     * @param programme curriculum level requirement id
     */
    public function getProgCurrLevelGroupRequirements($params)
    {
        if (count($params)) {
            $filters = $params['ExamProcessingSearch'];
            $progCurrID = $filters['prog_curriculum_id'];
            $progCurrID = intval($progCurrID);
            if (isset($progCurrID)) {
                $rows = (new \yii\db\Query())
                  ->select(['prog_curr_group_requirement.prog_curr_group_requirement_id',
                    'prog_curr_group_requirement.prog_curr_level_req_id',
                    'prog_curr_group_requirement.prog_curr_course_group_id',
                    'prog_curr_group_requirement.min_group_courses',
                    'prog_curr_group_requirement.group_pass_type',
                    'prog_curr_group_requirement.min_group_pass',
                    'prog_curr_group_requirement.gpa_pass_type',
                    'prog_curr_group_requirement.gpa_courses',
                    'prog_curr_group_requirement.extra_courses_processing',
                    'prog_curr_course_group.course_group_id',
                    'prog_curr_course_group.course_group_name',
                    'prog_curr_course_group.course_group_desc',
                    'prog_curr_course_group.course_group_type',
                    'prog_curr_level_requirement.prog_curriculum_id',
                    'prog_curr_level_requirement.prog_study_level'])
                  ->from('smis.prog_curr_group_requirement')
                  ->innerJoin('smis.prog_curr_course_group', 'prog_curr_group_requirement.prog_curr_course_group_id = prog_curr_course_group.course_group_id')
                  ->innerJoin('smis.prog_curr_level_requirement', 'prog_curr_group_requirement.prog_curr_level_req_id = prog_curr_level_requirement.prog_curr_level_req_id')
                  ->where(['smis.prog_curr_level_requirement.prog_curriculum_id' => $progCurrID])
                  ->orderBy(['prog_curr_level_requirement.prog_study_level' => SORT_ASC])
                  ->createCommand()
                  ->queryAll();
                return $rows ?? null;
            }
        }
    }#end function

    /**
     * get program curriculum group courses
     * @param $progCurrID
     * @return array|void|\yii\db\DataReader|null
     * @throws \yii\db\Exception
     */
    public function getProgCurrGroupCourses($progCurrID)
    {
        if (isset($progCurrID)) {

            $rows = (new \yii\db\Query())
              ->select(['prog_curr_group_req_course.prog_curr_group_req_course_id',
                'prog_curr_group_req_course.prog_curr_group_requirement_id',
                'prog_curr_group_req_course.prog_curriculum_course_id',
                'prog_curr_group_req_course.credit_factor',
                'org_prog_curr_course.prog_curriculum_id',
                'org_prog_curr_course.course_id',
                'org_courses.course_code',
                'org_courses.course_name'])
              ->from('smis.prog_curr_group_req_course')
              ->innerJoin('smis.org_prog_curr_course', 'prog_curr_group_req_course.prog_curriculum_course_id = org_prog_curr_course.prog_curriculum_course_id')
              ->innerJoin('smis.org_courses', 'org_prog_curr_course.course_id = org_courses.course_id')
              ->where(['org_prog_curr_course.prog_curriculum_id' => $progCurrID])
              ->createCommand()
              ->queryAll();
            return $rows ?? null;
        }
    }#end function

    public function postStudentRecommendation($studentRecomendation)
    {
        if (is_array($studentRecomendation) && count($studentRecomendation)) {
            foreach ($studentRecomendation as $key => $value) {
                $row = (new \yii\db\Query())
                  ->select(['smis.sm_exam_result.exam_result_id'])
                  ->from('smis.sm_exam_result')
                  ->where(['sm_exam_result.fk_registration_number' => $value['fk_registration_number'], 'sm_exam_result.level_of_study' => $value['level_of_study']])
                  ->createCommand()
                  ->queryOne();
            }
            if ($row) {
                $exam_result_id = $row['exam_result_id'];
                //echo "Updated  =>$exam_result_id";
                $arrayValues = [
                  'fk_registration_number' => $value['fk_registration_number'],
                  'level_of_study' => $value['level_of_study'],
                  'result' => $value['result'],
                  'ext_result' => $value['ext_result'],
                  'total_marks' => $value['total_marks'],
                  'levelTrail' => $value['levelTrail'],
                  'gpa' => $value['gpa'],
                  'gpa_courses' => $value['gpa_courses'],
                  'courses_taken' => $value['courses_taken'],];
                $command = Yii::$app->db->createCommand()->update('smis.sm_exam_result', $arrayValues, 'exam_result_id =' . $exam_result_id)->execute();
            } else {
                #insert
                $command = Yii::$app->db->createCommand()->insert('smis.sm_exam_result', [
                  'fk_registration_number' => $value['fk_registration_number'],
                  'level_of_study' => $value['level_of_study'],
                  'result' => $value['result'],
                  'ext_result' => $value['ext_result'],
                  'total_marks' => $value['total_marks'],
                  'levelTrail' => $value['levelTrail'],
                  'gpa' => $value['gpa'],
                  'gpa_courses' => $value['gpa_courses'],
                  'courses_taken' => $value['courses_taken'],
                ])->execute();
            }
        }
    }

}#end class
