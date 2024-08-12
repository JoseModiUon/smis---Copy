<?php

namespace app\modules\examManagement\models\search;

use app\models\CrProgCurrTimetable;
use app\models\ExStudentCourses;
use app\models\Student;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

class ProvisionalViewTranscriptPDF extends ExStudentCourses
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'progCurriculum.programme.prog_code',
                    'progCurriculum.programme.prog_full_name'
                ],
                'safe'
            ]
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

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = ExStudentCourses::find()
            ->select([
                'ex_student_courses.course_registration_id',
                'ex_student_courses.student_courses_id',
                'ex_student_courses.final_mark',
                'ex_student_courses.grade',
                'org_courses.course_code',
                'org_courses.course_name',
                'org_courses.academic_hours',
                'org_prog_curr_semester_group.programme_level'

            ])
            ->distinct()
            ->innerJoinWith(['timetable' => function (ActiveQuery $q) {
                $q->innerJoinWith(['progCurriculumSemGroup']);
                $q->innerJoinWith(['orgProgCurrCourse' => function (ActiveQuery $curr) {
                    $curr->joinWith(['course', 'progCurriculum', 'semesterCode', 'academicLevels']);
                }]);
            }])
            ->where(['like', 'course_registration_id', $params['reg_no'] . '%', false]);

        $query->asArray();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }
}
