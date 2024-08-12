<?php

/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-05-13 16:05:51 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-05-13 17:44:08
 * @Description: file:///home/user/Documents/smis/models/search/FeeItemsSearch.php
 */


namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeeItems;

/**
 * FeeItemsSearch represents the model behind the search form of `app\models\FeeItems`.
 */
class FeeItemsSearch extends FeeItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_code', 'priority'], 'integer'],
            [['fee_description', 'naration', 'fee_type', 'publish', 'fee_code_alias'], 'safe'],
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
        $query = FeeItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fee_code' => $this->fee_code,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['ilike', 'fee_description', $this->fee_description])
            ->andFilterWhere(['ilike', 'naration', $this->naration])
            ->andFilterWhere(['ilike', 'fee_type', $this->fee_type])
            ->andFilterWhere(['ilike', 'publish', $this->publish])
            ->andFilterWhere(['ilike', 'fee_code_alias', $this->fee_code_alias]);

        return $dataProvider;
    }
}
