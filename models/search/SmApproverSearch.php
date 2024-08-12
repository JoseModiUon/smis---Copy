<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmApprover;

/**
 * SmApproverSearch represents the model behind the search form of `app\models\SmApprover`.
 */
class SmApproverSearch extends SmApprover
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approver_id', 'level'], 'integer'],
            [['approver', 'status'], 'safe'],
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
        $query = SmApprover::find();

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
            'approver_id' => $this->approver_id,
            'level' => $this->level,
        ]);

        $query->andFilterWhere(['ilike', 'approver', $this->approver])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
