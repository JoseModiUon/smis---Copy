<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\examManagement\models\ProgCurrLevelRequirement;

/**
 * ProgCurrLevelRequirementSearch represents the model behind the search form of `app\modules\examManagement\models\ProgCurrLevelRequirement`.
 */
class ProgCurrLevelRequirementSearch extends ProgCurrLevelRequirement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curr_level_req_id', 'prog_curriculum_id', 'prog_study_level', 'min_courses_taken', 'min_pass_courses', 'gpa_courses', 'gpa_weight'], 'integer'],
            [['pass_type', 'gpa_choice', 'pass_result', 'pass_recommendation', 'fail_type', 'fail_result', 'fail_recommendation', 'incomplete_result', 'incomplete_recommendation'], 'safe'],
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
        $query = ProgCurrLevelRequirement::find();

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
            'prog_curr_level_req_id' => $this->prog_curr_level_req_id,
            'prog_curriculum_id' => $this->prog_curriculum_id,
            'prog_study_level' => $this->prog_study_level,
            'min_courses_taken' => $this->min_courses_taken,
            'min_pass_courses' => $this->min_pass_courses,
            'gpa_courses' => $this->gpa_courses,
            'gpa_weight' => $this->gpa_weight,
        ]);

        $query->andFilterWhere(['ilike', 'pass_type', $this->pass_type])
            ->andFilterWhere(['ilike', 'gpa_choice', $this->gpa_choice])
            ->andFilterWhere(['ilike', 'pass_result', $this->pass_result])
            ->andFilterWhere(['ilike', 'pass_recommendation', $this->pass_recommendation])
            ->andFilterWhere(['ilike', 'fail_type', $this->fail_type])
            ->andFilterWhere(['ilike', 'fail_result', $this->fail_result])
            ->andFilterWhere(['ilike', 'fail_recommendation', $this->fail_recommendation])
            ->andFilterWhere(['ilike', 'incomplete_result', $this->incomplete_result])
            ->andFilterWhere(['ilike', 'incomplete_recommendation', $this->incomplete_recommendation]);

        return $dataProvider;
    }
}
