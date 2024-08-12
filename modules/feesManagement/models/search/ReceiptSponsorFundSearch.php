<?php

namespace app\modules\feesManagement\models\search;

use app\modules\feesManagement\models\ReceiptSponsorFund;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReceiptSponsorFundSearch represents the model behind the search form of `app\models\ReceiptSponsorFund`.
 */
class ReceiptSponsorFundSearch extends ReceiptSponsorFund
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_sponsor_fund_id', 'sponsor_id', 'no_of_beneficiaries'], 'integer'],
            [['amount'], 'number'],
            // [['pv_no', 'cheque_no']],
            [['trans_type', 'description', 'receipt_date', 'pv_no', 'cheque_no', 'academic_session', 'user_id', 'post_date', 'source_reference'], 'safe'],
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
        $query = ReceiptSponsorFund::find();

        $query->innerJoinWith('sponsor');

        if (array_key_exists('sponsor_id', $params)) {
            $query->where(['sm_student_sponsor.sponsor_id' => $params['sponsor_id']]);
        }



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
            'receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id,
            'sponsor_id' => $this->sponsor_id,
            'amount' => $this->amount,
            'receipt_date' => $this->receipt_date,
            'post_date' => $this->post_date,
            'no_of_beneficiaries' => $this->no_of_beneficiaries,
        ]);

        $query->andFilterWhere(['ilike', 'trans_type', $this->trans_type])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'pv_no', $this->pv_no])
            ->andFilterWhere(['ilike', 'cheque_no', $this->cheque_no])
            ->andFilterWhere(['ilike', 'academic_session', $this->academic_session])
            ->andFilterWhere(['ilike', 'user_id', $this->user_id])
            ->andFilterWhere(['ilike', 'source_reference', $this->source_reference]);

        return $dataProvider;
    }
}
