<?php

namespace app\modules\courseRegistration\models\search;

use app\models\CrCourseRegistration;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\courseRegistration\models\CrProgCurrTimetable;
use yii\db\ActiveQuery;

/**
 * FullClassListSearch represents the model behind the search form of `app\modules\courseRegistration\models\CrProgCurrTimetable`.
 */
class IndividualClassListSearch extends CrProgCurrTimetable
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'course_name',
            'course_code',
            'acad_session_name',
            'study_centre_name',
            'study_group_name',
            'semester_type',
            'semster_name',
            'programme_level',
            'academic_level_name'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return
            [
                [[
                    'course_name', 'course_code', 'acad_session_name', 'study_centre_name', 'study_group_name', 'semester_type', 'semster_name',
                    'programme_level', 'academic_level_name'
                ], 'safe']
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

        $query = CrCourseRegistration::find()
            ->select([
                'cr_course_registration.student_course_reg_id',
                'cr_course_registration.timetable_id',
                'sm_student.student_id',
                'sm_student.student_number',
                'sm_student.surname',
                'sm_student.other_names',
                'sm_student.primary_email',
                'sm_student.alternative_email',
                'sm_student.primary_phone_no',
                'sm_student.alternative_phone_no',
                
            ])
            ->innerJoinWith(['smStudentSemSessionProgress' => function (ActiveQuery $r) {
                $r->innerJoinWith(['academicProgress' => function (ActiveQuery $ap) {
                    $ap->innerJoinWith(['studentProgCurriculum' => function (ActiveQuery $str) {
                        $str->innerJoinWith(['student']);
                    }]);
                }]);
            }])
            ->where([
                'cr_course_registration.timetable_id' => $params['timetable_id'],
            ]);

        $query->asArray();

        $dataProvider =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomme  nt the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
