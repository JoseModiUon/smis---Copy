<?php

namespace app\modules\examManagement\models\search;

use app\models\CrProgCurrTimetable;
use app\models\ExStudentCourses;
use app\models\SmExamResult;
use app\models\SmStudent;
use app\models\Student;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

class ConsolidatedMarksheetSearch extends ExStudentCourses
{
    public $acad_session_id;
    public $academic_level_id;
    public $programme_level_id;
    public $study_group_id;
    public $std_category_id;

    public $prog_curriculum_id;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'acad_session_id',
                    'academic_level_id',
                    'programme_level_id',
                    'study_group_id',
                    'std_category_id',
                    'prog_curriculum_id',
                ],
                'required'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'acad_session_id' => 'Academic Year',
            'prog_curriculum_id' => 'Programme',
            'academic_level_id' => 'Course Level',
            'programme_level_id' => 'Student Level',
            'std_category_id' => 'Module',
            'study_group_id' => 'Group',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function populateFormValues($params)
    {
        foreach ($params as $key => $param) {
            if (array_key_exists($key, get_object_vars($this))) {
                $this->$key = $param;
            }
        }
    }


    private function filterData(array $records): array
    {
        $data = [];

        foreach ($records as $index => $record) {
            if ($index == 0) {
                $data[] = $record;
            } else {
                if (in_array($record['student_id'], array_column($data, 'student_id'))) {

                    foreach ($data as $key => $value) {
                        if ($record['student_id'] == $value['student_id']) {
                            $data[$key] = array_merge($data[$key], [
                                'course_id_' . $index + 1 => $record['course_id'],
                                'course_code_' . $index + 1 => $record['course_code'],
                                'course_name_' . $index + 1 => $record['course_name'],
                                'final_mark_' . $index + 1 => $record['final_mark'],
                                'grade_' . $index + 1 => $record['grade'],
                            ]);
                        }
                    }
                } else {
                    $data[] = $record;
                }
            }
        }

        foreach ($data as $key => $record) {
            $total = 0;
            $count = 0;
            foreach ($record as $record_key => $value) {
                if (str_starts_with($record_key, 'final_mark')) {
                    $total += $value;
                    $count++;
                }
            }
            $data[$key] = array_merge(['total_mark' => $total, 'total_subjects' => $count, 'average_mark' => $total / $count], $record);
        }

        usort($data, fn ($a, $b) => $a['average_mark'] < $b['average_mark']);
        return $data;
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     */
    public function search(array $params): ArrayDataProvider
    {


        $query = ExStudentCourses::find()
            ->select([
                'ex_student_courses.course_registration_id',
                'ex_student_courses.student_courses_id',
                'ex_student_courses.final_mark',
                'ex_student_courses.grade',
                'org_prog_curr_course.course_id',
                'org_courses.course_name',
                'org_courses.course_code',
                'sm_student.student_number',
                'sm_student.student_id',
                'concat(sm_student.surname,\' \',sm_student.other_names) as name',
                'cr_prog_curr_timetable.timetable_id',
                'cr_prog_curr_timetable.prog_curriculum_course_id',
                'org_prog_curr_course.level_of_study'
            ])
            ->distinct()
            ->innerJoinWith(['timetable' => function (ActiveQuery $q) {
                $q->innerJoinWith(['crCourseRegistrations' => function (ActiveQuery $crg) {
                    $crg->innerJoinWith(['smStudentSemSessionProgress' => function (ActiveQuery $r) {
                        $r->innerJoinWith(['academicProgress' => function (ActiveQuery $ap) {
                            $ap->innerJoinWith(['studentProgCurriculum' => function (ActiveQuery $str) {
                                $str->innerJoinWith(['progCurriculum', 'admRefno', 'student', 'studentCategory']);
                            }]);
                            $ap->innerJoinWith(['acadSession']);
                        }]);
                    }]);
                }]);
                $q->innerJoinWith(['orgProgCurrCourse' => function (ActiveQuery $org) {
                    $org->innerJoinWith(['course']);
                }]);
                $q->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $sem) {
                    // $sem->innerJoinWith(['programmeLevel']);
                    $sem->innerJoinWith(['studyCentreGroup' => function (ActiveQuery $scg) {
                        $scg->innerJoinWith(['studyGroup']);
                    }]);
                }]);
            }])
            ->where([
                'org_academic_session.acad_session_id' => $params['acad_session_id'],
                // 'org_academic_levels.academic_level_id' => $params['academic_level_id'],
                'org_programme_curriculum.prog_curriculum_id' => $params['prog_curriculum_id'],
                // 'org_prog_level.programme_level_id' => $params['programme_level_id'],
                'org_prog_curr_course.level_of_study' => $params['programme_level_id'],
                'org_study_group.study_group_id' => $params['study_group_id'],
                'sm_student_category.std_category_id' => $params['std_category_id'],
            ])
            ->andWhere(
                "course_registration_id LIKE CONCAT('%', sm_student_programme_curriculum.registration_number, '%')"
            );

        $query->asArray();



        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->filterData($query->all()),
            'pagination' => false,
            'sort' => false,
        ]);

        $this->populateFormValues($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
