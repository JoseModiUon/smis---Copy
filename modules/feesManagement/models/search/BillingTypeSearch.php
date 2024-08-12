<?php

namespace app\modules\feesManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\BillingType;

/**
 * BillingTypeSearch represents the model behind the search form of `app\modules\feesManagement\models\BillingType`.
 */
class BillingTypeSearch extends BillingType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_type_id'], 'integer'],
            [['billing_type_desc'], 'safe'],
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
        $query = BillingType::find();

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
            'billing_type_id' => $this->billing_type_id,
        ]);

        $query->andFilterWhere(['ilike', 'billing_type_desc', $this->billing_type_desc]);

        return $dataProvider;
    }
}
