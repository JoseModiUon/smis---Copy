<?php

namespace app\modules\feesManagement\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\BankBranches;

/**
 * BankBranchesSearch represents the model behind the search form of `app\modules\studentRecords\models\BankBranches`.
 */
class BankBranchesSearch extends BankBranches
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_code', 'branch_name', 'bank_code'], 'safe'],
            [['branch_id'], 'integer'],
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
        $query = BankBranches::find();

        $query->joinWith('bank');

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
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['ilike', 'branch_code', $this->branch_code])
            ->andFilterWhere(['ilike', 'branch_name', $this->branch_name])
            ->andFilterWhere(['ilike', 'bank_code', $this->bank_code]);

        return $dataProvider;
    }
}
