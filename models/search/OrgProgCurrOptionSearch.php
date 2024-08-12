<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgCurrOption;

/**
 * OrgProgCurrOptionSearch represents the model behind the search form of `app\models\OrgProgCurrOption`.
 */
class OrgProgCurrOptionSearch extends OrgProgCurrOption
{
    public $programmeCurriculum;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_id'], 'integer'],
            [['option_code', 'option_name', 'degree_id', 'option_desc', 'option_status', 'progress_type','programmeCurriculum'], 'safe'],
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
        $query = OrgProgCurrOption::find();
        $query->joinWith('programmeCurriculum');

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

        $dataProvider->sort->attributes['programmeCurriculum'] = [

            'asc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_ASC],
            'desc' => ['org_programme_curriculum.prog_curriculum_desc' => SORT_DESC],
        ];


        // grid filtering conditions
        $query->andFilterWhere([
            'option_id' => $this->option_id,
            'prog_curriculum_id' => $this->prog_cur_id
        ]);

        $query->andFilterWhere(['ilike', 'option_code', $this->option_code])
            ->andFilterWhere(['ilike', 'option_name', $this->option_name])
            ->andFilterWhere(['ilike', 'org_programme_curriculum.prog_curriculum_desc', $this->programmeCurriculum])
            ->andFilterWhere(['ilike', 'option_desc', $this->option_desc])
            ->andFilterWhere(['ilike', 'option_status', $this->option_status])
            ->andFilterWhere(['ilike', 'progress_type', $this->progress_type]);

        return $dataProvider;
    }
}
