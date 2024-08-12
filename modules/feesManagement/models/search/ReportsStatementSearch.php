<?php

namespace app\modules\feesManagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\FeeTransactions;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\QueryBuilder;

/**
 * BankingSlipsSearch represents the model behind the search form about `app\modules\feesManagement\models\BankingSlips`.
 */
class ReportsStatementSearch extends FeeTransactions
{
    public $sponsor;
    public $receipt_sponsor_fund_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academic_progress_id', 'trans_date', 'trans_type', 'trans_amount', 'user_id'], 'required'],
            [['academic_progress_id', 'invoice_id'], 'default', 'value' => null],
            [['trans_id', 'academic_progress_id', 'invoice_id'], 'integer'],
            [['trans_date'], 'safe'],
            [['trans_amount', 'exchange_rate'], 'number'],
            [['trans_type'], 'string', 'max' => 25],
            [['trans_desc'], 'string', 'max' => 150],
            [['user_id'], 'string', 'max' => 30],
            [['receipt_status'], 'string', 'max' => 15],
            [['trans_id'], 'unique'],
            // [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoice_id' => 'invoice_id']],
            // [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmAcademicProgress::class, 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
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
        if (!array_key_exists('ReportsSearch', $params)) return;
        $params = $params['ReportsSearch'];
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
        // dd($params);
        $query = FeeTransactions::find();

        $query->joinWith(['bankingSlips' => function(ActiveQuery $t){
            $t->joinWith(['beneficiary' => function (ActiveQuery $q) {
                $q->joinWith(['receipt' => function (ActiveQuery $r) {
                    $r->joinWith('sponsor');
                }]);
            }]);
            $t->joinWith(['student']);
        }]);


        if (!empty($params)) {
            $query->where(['fss_receipt_sponsor_fund.receipt_sponsor_fund_id' => $params['recipient_sponsor_fund_id']]);
            $query->andWhere(['fss_banking_slips.reg_number' => $params['reg_number']]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


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
