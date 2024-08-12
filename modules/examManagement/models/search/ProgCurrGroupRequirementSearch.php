<?php

namespace app\modules\examManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\examManagement\models\ProgCurrGroupRequirement;

/**
 * ProgCurrGroupRequirementSearch represents the model behind the search form of `app\modules\examManagement\models\ProgCurrGroupRequirement`.
 */
class ProgCurrGroupRequirementSearch extends ProgCurrGroupRequirement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curr_group_requirement_id', 'prog_curr_level_req_id', 'prog_curr_course_group_id', 'min_group_courses', 'min_group_pass', 'gpa_courses'], 'integer'],
            [['group_pass_type', 'gpa_pass_type', 'extra_courses_processing'], 'safe'],
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
        $query = ProgCurrGroupRequirement::find();

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
            'prog_curr_group_requirement_id' => $this->prog_curr_group_requirement_id,
            'prog_curr_level_req_id' => $this->prog_curr_level_req_id,
            'prog_curr_course_group_id' => $this->prog_curr_course_group_id,
            'min_group_courses' => $this->min_group_courses,
            'min_group_pass' => $this->min_group_pass,
            'gpa_courses' => $this->gpa_courses,
        ]);

        $query->andFilterWhere(['ilike', 'group_pass_type', $this->group_pass_type])
            ->andFilterWhere(['ilike', 'gpa_pass_type', $this->gpa_pass_type])
            ->andFilterWhere(['ilike', 'extra_courses_processing', $this->extra_courses_processing]);

        return $dataProvider;
    }
}
