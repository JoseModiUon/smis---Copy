<?php

namespace app\modules\studentRecords\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\studentRecords\models\SponsorBeneficiary;

/**
 * SponsorBeneficiarySearch represents the model behind the search form of `app\modules\studentRecords\models\SponsorBeneficiary`.
 */
class SponsorBeneficiarySearch extends SponsorBeneficiary
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_beneficiary_id', 'receipt_sponsor_fund_id'], 'integer'],
            [['reg_no', 'name', 'trans_type', 'transfer_status', 'post_date', 'user_id', 'file_path'], 'safe'],
            [['amount'], 'number'],
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
        $query = SponsorBeneficiary::find();

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
            'sponsor_beneficiary_id' => $this->sponsor_beneficiary_id,
            'receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id,
            'amount' => $this->amount,
            'post_date' => $this->post_date,
        ]);

        $query->andFilterWhere(['ilike', 'reg_no', $this->reg_no])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'trans_type', $this->trans_type])
            ->andFilterWhere(['ilike', 'transfer_status', $this->transfer_status])
            ->andFilterWhere(['ilike', 'user_id', $this->user_id])
            ->andFilterWhere(['ilike', 'file_path', $this->file_path]);

        return $dataProvider;
    }
}
