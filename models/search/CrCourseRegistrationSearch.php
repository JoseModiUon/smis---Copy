<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CrCourseRegistration;

/**
 * CrCourseRegistrationSearch represents the model behind the search form of `app\models\CrCourseRegistration`.
 */
class CrCourseRegistrationSearch extends CrCourseRegistration
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_course_reg_id', 'timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id'], 'integer'],
            [['registration_date', 'source_ipaddress', 'userid'], 'safe'],
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
        $query = CrCourseRegistration::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'student_course_reg_id' => $this->student_course_reg_id,
            'timetable_id' => $this->timetable_id,
            'student_semester_session_id' => $this->student_semester_session_id,
            'course_registration_type_id' => $this->course_registration_type_id,
            'registration_date' => $this->registration_date,
            'course_reg_status_id' => $this->course_reg_status_id,
        ]);

        $query->andFilterWhere(['ilike', 'source_ipaddress', $this->source_ipaddress])
            ->andFilterWhere(['ilike', 'userid', $this->userid]);

        return $dataProvider;
    }
}
