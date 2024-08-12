<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Banks;

/**
 * BankSearch represents the model behind the search form of `app\models\Banks`.
 */
class BankSearch extends Banks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_id'], 'integer'],
            [['bank_code', 'bank_name'], 'safe'],
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
        $query = Banks::find();

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
            'bank_id' => $this->bank_id,
        ]);

        $query->andFilterWhere(['ilike', 'bank_code', $this->bank_code])
            ->andFilterWhere(['ilike', 'bank_name', $this->bank_name]);

        return $dataProvider;
    }
}
