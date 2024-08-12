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

class ProvisionalTranscriptSearch extends ExStudentCourses
{
    public $student_id;

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
                'timetable_id',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student.student_number',
            ])
            ->innerJoinWith(['studentProgrammeCurriculums' => function (ActiveQuery $stu) {
                $stu->innerJoinWith(['progCurriculum' => function (ActiveQuery $pr) {
                    $pr->innerJoinWith(['orgProgCurrCourses' => function (ActiveQuery $org) {
                        $org->innerJoinWith(['timetable' => function (ActiveQuery $tt) {
                            $tt->innerJoinWith(['exStudentCourses']);
                        }]);
                    }]);
                }]);
            }])
            ->where("course_registration_id LIKE CONCAT('%', sm_student_programme_curriculum.registration_number, '%')");

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
        if (!empty($params)) {
            $query->andFilterWhere([
                'sm_student.student_id' => $params['ProvisionalTranscriptSearch']['student_id']
            ]);
        }

        return $dataProvider;
    }
}
