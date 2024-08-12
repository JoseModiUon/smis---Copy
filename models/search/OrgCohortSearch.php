<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgCohort;

/**
 * OrgCohortSearchNew represents the model behind the search form of `app\models\OrgCohort`.
 */
class OrgCohortSearch extends OrgCohort
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cohort_id'], 'integer'],
            [['cohort_desc', 'cohort_year', 'adm_start_date', 'adm_end_date', 'cohort_status'], 'safe'],
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
        $query = OrgCohort::find();

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
            'cohort_id' => $this->cohort_id,
            'adm_start_date' => $this->adm_start_date,
            'adm_end_date' => $this->adm_end_date,
        ]);

        $query->andFilterWhere(['ilike', 'cohort_desc', $this->cohort_desc])
            // ->andFilterWhere(['ilike', 'cohort_year ', $this->cohort_year ])
            ->andFilterWhere(['ilike', 'cohort_status', $this->cohort_status]);

        return $dataProvider;
    }
}
