<?php

namespace app\modules\feesManagement\models\search;

use app\models\Student;
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
class ReportsSearch extends BankingSlips
{
    public $sponsor;
<<<<<<< HEAD
    public $receipt_sponsor_fund_id;
    public $student;
=======
    public $reg_number;
>>>>>>> c176a92 (Change search to take in Reg Number)

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
        $query = Student::find();

        if(!empty($params)) {
<<<<<<< HEAD
            $query->andWhere(['sm_student.student_id' => $params['ReportsSearch']['student']]);
=======
        
            $query->where(['fss_banking_slips.reg_number' => $params['ReportsSearch']['reg_number']]);
           
>>>>>>> c176a92 (Change search to take in Reg Number)
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
