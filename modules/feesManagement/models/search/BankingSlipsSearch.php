<?php

namespace app\modules\feesManagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\BankingSlips;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\QueryBuilder;

/**
 * BankingSlipsSearch represents the model behind the search form about `app\modules\feesManagement\models\BankingSlips`.
 */
class BankingSlipsSearch extends BankingSlips
{
    public $bank_account;
    public $payment_mode;
    public $posting;
    public $branch;
    public $payment_type;
    public $deposit_from_date;
    public $deposit_to_date;
    public $narrative;
    public $transaction_no;
    public $deposit_amount;
    public $post_amount;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trans_id', 'receipt_no', 'user_id', 'file_id', 'sponsor_beneficiary_id', 'bank_id'], 'integer'],
            [['deposit_date', 'deposit_type', 'reg_number', 'other_names', 'post_status', 'post_comment', 'account_no', 'process_date', 'batch_no', 'pay_mode', 'chq_no', 'drawer_account', 'trans_reference', 'branch_code', 'last_update', 'drawer_name', 'student_type', 'source_reference', 'registration_number', 'value_date', 'bank_number'], 'safe'],
            [['deposit_amount', 'run_balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function populateFormValues($params)
    {
        if (!array_key_exists('BankingSlipsSearch', $params)) return;
        $params = $params['BankingSlipsSearch'];
        foreach ($params as $key => $param) {
            if (array_key_exists($key, get_object_vars($this))) {
                $this->$key = $param;
            }
        }
    }

    private function addParams($query, $params)
    {
        if (!array_key_exists('BankingSlipsSearch', $params)) return;
        $params = $params['BankingSlipsSearch'];
        foreach ($params as $key => $val) {
            if (!$val) continue;
            if ($key == 'bank_account') {
                // $query->andWhere(['brank_account_id' => $val]);
            }
            if ($key == 'payment_mode') {
                $query->andWhere(['payment_mode_id' => $val]);
            }
            if ($key == 'posting') {
                $query->andWhere(['like', 'post_status', $val . '%', false]);
            }
            if ($key == 'branch') {
                // $query->andWhere(['fss_bank_branches.branch_code' => $val]);
            }
            if ($key == 'payment_type') {
                $query->andWhere(['fee_code' => $val]);
            }
            if ($key == 'deposit_from_date') {
                $query->andWhere(['>', 'deposit_date', date('Y-m-d H:i:s', strtotime($val))]);
            }
            if ($key == 'deposit_to_date') {
                $query->andWhere(['<', 'deposit_date', date('Y-m-d H:i:s', strtotime($val))]);
            }
            if ($key == 'transaction_no') {
                $query->andWhere(['fss_banking_slips.trans_id' => $val]);
            }
            if ($key == 'deposit_amount') {
                $query->andWhere(['fss_sponsor_beneficiary.amount' => $val]);
            }
            if ($key == 'post_amount') {
                $query->andWhere(['fss_banking_slips.deposit_amount' => $val]);
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

        // $query = BankingSlips::find();
        $query = (new Query());

        // $query->select('*');
        $query->from('smis.fss_banking_slips');
        $query->leftJoin('smis.fss_banks', 'fss_banks.brank_id = fss_banking_slips.bank_id');
        // $query->leftJoin('smis.fss_bank_branches', 'fss_bank_branches.bank_code = fss_banks.bank_code');
        // $query->innerJoin('smis.fss_bank_accounts', 'fss_bank_accounts.branch_code=fss_bank_branches.branch_code');
        $query->leftJoin('smis.fss_payment_modes', 'fss_payment_modes.payment_mode_id=fss_banking_slips.pay_mode');
        $query->leftJoin('smis.fss_fee_items', 'fss_fee_items.fee_code= fss_banking_slips.deposit_type');
        $query->leftJoin('smis.fss_sponsor_beneficiary', 'fss_sponsor_beneficiary.sponsor_beneficiary_id = fss_banking_slips.sponsor_beneficiary_id');


        $this->addParams($query, $params);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $this->populateFormValues($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'trans_id' => $this->trans_id,
            'deposit_date' => $this->deposit_date,
            'deposit_amount' => $this->deposit_amount,
            'receipt_no' => $this->receipt_no,
            'process_date' => $this->process_date,
            'run_balance' => $this->run_balance,
            'last_update' => $this->last_update,
            'user_id' => $this->user_id,
            'value_date' => $this->value_date,
            'file_id' => $this->file_id,
            'sponsor_beneficiary_id' => $this->sponsor_beneficiary_id,
            'bank_id' => $this->bank_id,
        ]);

        $query->andFilterWhere(['like', 'deposit_type', $this->deposit_type])
            ->andFilterWhere(['like', 'reg_number', $this->reg_number])
            ->andFilterWhere(['like', 'other_names', $this->other_names])
            ->andFilterWhere(['like', 'post_status', $this->post_status])
            ->andFilterWhere(['like', 'post_comment', $this->post_comment])
            ->andFilterWhere(['like', 'account_no', $this->account_no])
            ->andFilterWhere(['like', 'batch_no', $this->batch_no])
            ->andFilterWhere(['like', 'pay_mode', $this->pay_mode])
            ->andFilterWhere(['like', 'chq_no', $this->chq_no])
            ->andFilterWhere(['like', 'drawer_account', $this->drawer_account])
            ->andFilterWhere(['like', 'trans_reference', $this->trans_reference])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'drawer_name', $this->drawer_name])
            ->andFilterWhere(['like', 'student_type', $this->student_type])
            ->andFilterWhere(['like', 'source_reference', $this->source_reference])
            ->andFilterWhere(['like', 'registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'bank_number', $this->bank_number]);

        // dd($query->createCommand()->getRawSql());
        return $dataProvider;
    }
}
