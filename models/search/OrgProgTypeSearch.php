<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrgProgType;

/**
 * OrgProgTypeSearch represents the model behind the search form of `app\models\OrgProgType`.
 */
class OrgProgTypeSearch extends OrgProgType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_type_id', 'prog_type_order'], 'integer'],
            [['prog_type_code', 'prog_type_name', 'status'], 'safe'],
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
        $query = OrgProgType::find();

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
            'prog_type_id' => $this->prog_type_id,
            'prog_type_order' => $this->prog_type_order,
        ]);

        $query->andFilterWhere(['ilike', 'prog_type_code', $this->prog_type_code])
            ->andFilterWhere(['ilike', 'prog_type_name', $this->prog_type_name])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
