<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmIntakes;

/**
 * SmIntakesSearch represents the model behind the search form of `app\models\SmIntakes`.
 */
class SmIntakesSearch extends SmIntakes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intake_code', 'acad_session_id'], 'integer'],
            [['intake_name'], 'safe'],
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
        $query = SmIntakes::find();

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
            'intake_code' => $this->intake_code,
            'acad_session_id' => $this->acad_session_id,
        ]);

        $query->andFilterWhere(['ilike', 'intake_name', $this->intake_name]);

        return $dataProvider;
    }
}
