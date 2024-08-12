<?php

namespace app\modules\feesManagement\models\search;

use app\modules\feesManagement\models\BankAccounts;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BankAccountsSearch represents the model behind the search form of `app\modules\studentRecords\models\BankAccounts`.
 */
class BankAccountsSearch extends BankAccounts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_account', 'branch_code', 'account_no', 'account_details', 'account_type', 'min_amount', 'max_amount', 'currency_id'], 'safe'],
            [['brank_account_id'], 'integer'],
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
        $query = BankAccounts::find();
        // $query->innerJoinWith('branch');

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
            'brank_account_id' => $this->brank_account_id,
        ]);

        $query->andFilterWhere(['ilike', 'bank_account', $this->bank_account])
            ->andFilterWhere(['ilike', 'branch_code', $this->branch_code])
            ->andFilterWhere(['ilike', 'account_no', $this->account_no])
            ->andFilterWhere(['ilike', 'account_details', $this->account_details])
            ->andFilterWhere(['ilike', 'account_type', $this->account_type])
            ->andFilterWhere(['ilike', 'min_amount', $this->min_amount])
            ->andFilterWhere(['ilike', 'max_amount', $this->max_amount])
            ->andFilterWhere(['ilike', 'currency_id', $this->currency_id]);

        return $dataProvider;
    }
}
