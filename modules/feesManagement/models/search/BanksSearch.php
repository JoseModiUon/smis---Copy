<?php

namespace app\modules\feesManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\Banks;

/**
 * BanksSearch represents the model behind the search form of `app\modules\feesManagement\models\Banks`.
 */
class BanksSearch extends Banks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_code', 'bank_name'], 'safe'],
            [['brank_id'], 'integer'],
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
            'brank_id' => $this->brank_id,
        ]);

        $query->andFilterWhere(['ilike', 'bank_code', $this->bank_code])
            ->andFilterWhere(['ilike', 'bank_name', $this->bank_name]);
        $query->orderBy(['bank_code' => SORT_ASC]);

        return $dataProvider;
    }
}
