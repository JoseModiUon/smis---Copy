<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgCountry;

/**
 * OrgCountrySearch represents the model behind the search form of `app\models\OrgCountry`.
 */
class OrgCountrySearch extends OrgCountry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_code', 'country_name', 'continent', 'region', 'code2', 'nationality'], 'safe'],
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
        $query = OrgCountry::find();

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
        $query->andFilterWhere(['ilike', 'country_code', $this->country_code])
            ->andFilterWhere(['ilike', 'country_name', $this->country_name])
            ->andFilterWhere(['ilike', 'continent', $this->continent])
            ->andFilterWhere(['ilike', 'region', $this->region])
            ->andFilterWhere(['ilike', 'code2', $this->code2])
            ->andFilterWhere(['ilike', 'nationality', $this->nationality]);

        return $dataProvider;
    }
}
