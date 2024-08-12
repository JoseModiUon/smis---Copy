<?php

namespace app\modules\feesManagement\models\search;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\SponsorBeneficiary;

/**
 * SponsorBeneficiary represents the model behind the search form of `app\models\SponsorBeneficiary`.
 */
class SponsorBeneficiarySearch extends SponsorBeneficiary
{
    public $bank_account;
    public $payment_mode;
    public $branch;
    public $payment_type;
    public $posting;
    public $receipt_no;
    public $payment_type_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_account', 'payment_mode', 'branch', 'payment_type', 'posting'], 'required'],
            [['sponsor_beneficiary_id', 'receipt_sponsor_fund_id'], 'integer'],
            [['reg_no', 'name', 'trans_type', 'transfer_status', 'post_date', 'user_id', 'receipt_no'], 'safe'],
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
     * populate form values after refresh
     *
     * @param [type] $params
     * @return void
     */
    private function populateFormValues($params)
    {
        foreach ($params as $key => $param) {
            if (array_key_exists($key, get_object_vars($this))) {
                $this->$key = $param;
            }
        }
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

        $query->joinWith('bankingSlips');

        if (array_key_exists('receipt_sponsor_fund_id', $params)) {
            $query->where(['receipt_sponsor_fund_id' => $params['receipt_sponsor_fund_id']]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $dataProvider->sort->attributes['receipt_no'] = [

            'asc' => ['smis.fss_banking_slips.receipt_no' => SORT_ASC],
            'desc' => ['smis.fss_banking_slips.receipt_no' => SORT_DESC],
        ];


        $this->populateFormValues($params);
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
            ->andFilterWhere(['ilike', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
