<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgUnitTypes;

/**
 * OrgUnitTypesSearch represents the model behind the search form of `app\models\OrgUnitTypes`.
 */
class OrgUnitTypesSearch extends OrgUnitTypes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_type_id'], 'integer'],
            [['unit_type_name', 'status'], 'safe'],
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
        $query = OrgUnitTypes::find();

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
            'unit_type_id' => $this->unit_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'unit_type_name', $this->unit_type_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
