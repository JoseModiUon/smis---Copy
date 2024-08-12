<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PaymentMode;

/**
 * PaymentModeSearch represents the model behind the search form of `app\models\PaymentMode`.
 */
class PaymentModeSearch extends PaymentMode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode_code', 'description'], 'safe'],
            [['mode_flag'], 'number'],
            [['payment_mode_id'], 'integer'],
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
        $query = PaymentMode::find();

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
            'mode_flag' => $this->mode_flag,
            'payment_mode_id' => $this->payment_mode_id,
        ]);

        $query->andFilterWhere(['ilike', 'mode_code', $this->mode_code])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}
