<?php

namespace app\modules\examManagement\models\search;

use app\models\CrProgCurrTimetable;
use app\models\ExStudentCourses;
use app\models\SmStudent;
use app\models\Student;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

class SenateReportsSearch extends ExStudentCourses
{
    public $student_id;
    public $types;
    public $prog_curriculum_id;
    public $acad_session_id;
    public $academic_level;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'student_id'
                ],
                'required'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id' => 'Registration No',
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

        $query = Student::find()
            ->select([
                'sm_student.student_id',
                'sm_student.student_number',
                'sm_student.gender',
                'sm_exam_result.ext_result',
                'sm_exam_result.courses_taken',
                'timetable_id',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student.student_number',
                'org_programme_curriculum.prog_curriculum_id',
                'org_academic_session_semester.acad_session_id'
            ])->innerJoinWith(['examResult' => function (ActiveQuery $exam) use ($params) {
                $exam->where(['sm_exam_result.level_of_study' => $params['SenateReportsSearch']['academic_level']])
                    ->andWhere(['ilike', 'sm_exam_result.result', $params['SenateReportsSearch']['types'] . '%', false]);
            }])
            ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $stu) {
                $stu->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                    $pr->innerJoinWith(['orgProgCurrCourses' => function (ActiveQuery $org) {
                        $org->innerJoinWith(['timetable' => function (ActiveQuery $tt) {
                            $tt->innerJoinWith(['exStudentCourses']);
                            $tt->innerJoinWith(['progCurriculumSemGroup' => function (ActiveQuery $semgrp) {
                                $semgrp->innerJoinWith(['progCurriculumSemester' => function (ActiveQuery $progCurr) {
                                    $progCurr->innerJoinWith(['acadSessionSemester']);
                                }]);
                            }]);
                        }]);
                    }]);
                }]);
            }]);

        $query->andWhere([
            'org_programme_curriculum.prog_curriculum_id' => $params['SenateReportsSearch']['prog_curriculum_id'],
            'org_prog_curr_course.level_of_study' => $params['SenateReportsSearch']['academic_level'],
            'org_academic_session_semester.acad_session_id' => $params['SenateReportsSearch']['acad_session_id'],
        ]);

        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }
}
