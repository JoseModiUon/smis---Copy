<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmWithdrawalType;

/**
 * SmWithdrawalTypeSearch represents the model behind the search form of `app\models\SmWithdrawalType`.
 */
class SmWithdrawalTypeSearch extends SmWithdrawalType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_type_id'], 'integer'],
            [['withdrawal_type_name'], 'safe'],
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
        $query = SmWithdrawalType::find();

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
            'withdrawal_type_id' => $this->withdrawal_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'withdrawal_type_name', $this->withdrawal_type_name]);

        return $dataProvider;
    }
}
