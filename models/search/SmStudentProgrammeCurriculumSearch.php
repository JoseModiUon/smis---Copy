<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmStudentProgrammeCurriculum;

/**
 * SmStudentProgrammeCurriculumSearch represents the model behind the search form of `app\models\SmStudentProgrammeCurriculum`.
 */
class SmStudentProgrammeCurriculumSearch extends SmStudentProgrammeCurriculum
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_prog_curriculum_id', 'student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['registration_number'], 'safe'],
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
        $query = SmStudentProgrammeCurriculum::find();

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
            'student_prog_curriculum_id' => $this->student_prog_curriculum_id,
            'student_id' => $this->student_id,
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'student_category_id' => $this->student_category_id,
            'adm_refno' => $this->adm_refno,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['ilike', 'registration_number', $this->registration_number]);

        return $dataProvider;
    }
}
