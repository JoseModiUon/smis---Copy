<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Student;
use Yii;
use yii\db\ActiveQuery;

/**
 * StudentSearch represents the model behind the search form of `app\models\Student`.
 */
class StudentSearch extends Student
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'sponsor'], 'integer'],
            [['student_number', 'surname', 'other_names', 'gender', 'country_code', 'date_of_birth', 'id_no', 'passport_no', 'service_number', 'blood_group', 'registration_date'], 'safe'],
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
        $query = Student::find()
            ->joinWith(['studentProgrammeCurriculums' => function (ActiveQuery $qa) {
                $qa->joinWith(['progCurriculum' => function (ActiveQuery $qc) {
                    $qc->joinWith(['prog' => function (ActiveQuery $prog) {
                        $prog->where(['in', 'prog_code', Yii::$app->session->get('userPrograms')]);
                    }]);
                }]);
            }]);



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
            'student_id' => $this->student_id,
            'dob' => $this->date_of_birth,
            'sponsor' => $this->sponsor,
            'registration_date' => $this->registration_date,
        ]);

        $query->andFilterWhere(['ilike', 'student_number', $this->student_number])
            ->andFilterWhere(['ilike', 'surname', $this->surname])
            ->andFilterWhere(['ilike', 'other_names', $this->other_names])
            ->andFilterWhere(['ilike', 'gender', $this->gender])
            ->andFilterWhere(['ilike', 'country_code', $this->country_code])
            ->andFilterWhere(['ilike', 'id_no', $this->id_no])
            ->andFilterWhere(['ilike', 'passport_no', $this->passport_no])
            ->andFilterWhere(['ilike', 'service_number', $this->service_number])
            ->andFilterWhere(['ilike', 'blood_group', $this->blood_group]);

        return $dataProvider;
    }
}
