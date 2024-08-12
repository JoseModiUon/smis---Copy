<?php

namespace app\modules\feesManagement\models;

use app\models\FeeItems;
use app\models\SmAcademicProgress;
use app\models\Student;
use app\modules\feesManagement\traits\FeeTrait;
use Yii;

/**
 * This is the model class for table "smis.fss_banking_slips".
 *
 * @property int $trans_id
 * @property string $deposit_date
 * @property string|null $deposit_type
 * @property float|null $deposit_amount
 * @property string|null $reg_number
 * @property string|null $other_names
 * @property string|null $post_status
 * @property string|null $post_comment
 * @property string|null $account_no
 * @property int|null $receipt_no
 * @property string|null $process_date
 * @property string|null $batch_no
 * @property string|null $pay_mode
 * @property string|null $chq_no
 * @property string|null $drawer_account
 * @property string|null $trans_reference
 * @property string|null $branch_code
 * @property float|null $run_balance
 * @property string|null $last_update
 * @property int|null $user_id
 * @property string|null $drawer_name
 * @property string|null $student_type
 * @property string|null $source_reference
 * @property string|null $registration_number
 * @property string|null $value_date
 * @property int|null $file_id
 * @property int|null $sponsor_beneficiary_id
 * @property int|null $bank_id
 * @property string|null $bank_number
 */
class BankingSlips extends \yii\db\ActiveRecord
{
    use FeeTrait;

    public $duplicate;
    public $reversal;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_banking_slips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deposit_date'], 'required'],
            [['deposit_date', 'process_date', 'last_update', 'value_date'], 'safe'],
            [['deposit_amount', 'run_balance'], 'number'],
            [['receipt_no', 'user_id', 'file_id', 'sponsor_beneficiary_id', 'bank_id'], 'default', 'value' => null],
            [['receipt_no', 'user_id', 'file_id', 'sponsor_beneficiary_id', 'bank_id', 'deposit_type', 'pay_mode'], 'integer'],
            [['post_comment', 'student_type'], 'string', 'max' => 20],
            [['reg_number', 'chq_no', 'branch_code', 'registration_number', 'bank_number'], 'string', 'max' => 30],
            [['other_names', 'trans_reference', 'drawer_name'], 'string', 'max' => 50],
            [['post_status'], 'string', 'max' => 15],
            [['account_no', 'batch_no', 'drawer_account'], 'string', 'max' => 25],
            [['source_reference'], 'string', 'max' => 35],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banks::class, 'targetAttribute' => ['bank_id' => 'brank_id']],
            [['sponsor_beneficiary_id'], 'exist', 'skipOnError' => true, 'targetClass' => SponsorBeneficiary::class, 'targetAttribute' => ['sponsor_beneficiary_id' => 'sponsor_beneficiary_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trans_id' => 'Trans ID',
            'deposit_date' => 'Deposit Date',
            'deposit_type' => 'Deposit Type',
            'deposit_amount' => 'Deposit Amount',
            'reg_number' => 'Reg Number',
            'other_names' => 'Other Names',
            'post_status' => 'Post Status',
            'post_comment' => 'Post Comment',
            'account_no' => 'Account No',
            'receipt_no' => 'Receipt No',
            'process_date' => 'Process Date',
            'batch_no' => 'Batch No',
            'pay_mode' => 'Pay Mode',
            'chq_no' => 'Chq No',
            'drawer_account' => 'Drawer Account',
            'trans_reference' => 'Trans Reference',
            'branch_code' => 'Branch Code',
            'run_balance' => 'Run Balance',
            'last_update' => 'Last Update',
            'user_id' => 'User ID',
            'drawer_name' => 'Drawer Name',
            'student_type' => 'Student Type',
            'source_reference' => 'Source Reference',
            'registration_number' => 'Registration Number',
            'value_date' => 'Value Date',
            'file_id' => 'File ID',
            'sponsor_beneficiary_id' => 'Sponsor Beneficiary ID',
            'bank_id' => 'Bank ID',
            'bank_number' => 'Bank Number',
            'payment_type_id' => 'Payment Type'
        ];
    }


    public function getStudent()
    {
        return $this->hasOne(Student::class, ['student_number' => 'reg_number']);
    }

    public function getBeneficiary()
    {
        return $this->hasOne(SponsorBeneficiary::class, ['sponsor_beneficiary_id' => 'sponsor_beneficiary_id']);
    }

    public function getFeePayments()
    {
        return $this->hasMany(FeePayments::class, ['trans_id' => 'trans_id']);
    }

    public function getBanks()
    {
        return $this->hasOne(Banks::class, ['brank_id' => 'bank_id']);
    }

    public function getPaymentTypes()
    {
        return $this->hasOne(FeeItems::class, ['fee_code' => 'deposit_type']);
    }
    public function getPaymentModes()
    {
        return $this->hasOne(PaymentModes::class, ['payment_mode_id' => 'pay_mode']);
    }

    public function getFeeTransactions()
    {
        return $this->hasOne(FeeTransactions::class, ['trans_id' => 'trans_id']);
    }


    public function postFeePayments(): bool
    {
        $beneficiary = SponsorBeneficiary::findOne($this->sponsor_beneficiary_id);
        $receipt = $beneficiary->getReceipt()->one();
        $student = Student::find()->where(['student_number' => $beneficiary->reg_no])->one();
        $fee_payment_records = [
            'receipt_no' => (string) $this->receipt_no,
            'trans_date' => date('Y-m-d'),
            'trans_amount' => $this->deposit_amount,
            'pay_mode' => $this->pay_mode,
            'collection_point_id' => $this->bank_id,
            'user_id' => (string) $this->user_id,
            'entry_date' => date('Y-m-d'),
            'trans_id' => $this->trans_id,
            'academic_session' => $receipt->academic_session,
            'authorized_by' => (string) $this->user_id,
            'authorized_date' => date('Y-m-d'),
            'receipt_status' => '',
            'exchange_rate' => '',
            'student_prog_curriculum_id' => $student->getStudentProgrammeCurriculums()->one()->student_prog_curriculum_id,
        ];
        $fee = FeePayments::find()->where(['trans_id' => $this->trans_id])->one();

        if (!$fee) {
            $fee_payment_model = $this->assign(new FeePayments(), $fee_payment_records);
            $ok = $fee_payment_model->save();
            if ($ok) {
                $fee_payment_model->refresh();
                $ok = $this->postFeeTransactions($fee_payment_model);

                if ($ok) {
                    $this->post_status = 'POSTED';
                    return $this->save();
                }
            }
        }
        return false;
    }

    /**
     * posting transactions to fee_transactions
     *
     * @param FeePayments $model
     * @return boolean
     */
    private function postFeeTransactions(FeePayments $model): bool
    {
        $academic_progress = SmAcademicProgress::find()->where(['student_prog_curriculum_id' => $model->student_prog_curriculum_id])->one();
        $BankingSlips = $this->findOne($model->trans_id);
        $records = [
            'trans_id' => $model->trans_id,
            'academic_progress_id' => $academic_progress->academic_progress_id,
            'trans_date' => $model->trans_date,
            'trans_type' => 'CR',
            'trans_amount' => $model->trans_amount,
            'trans_desc' => $BankingSlips->post_comment,
            'user_id' => $model->user_id,
            'receipt_status' => $model->receipt_status,
            'exchange_rate' => $model->exchange_rate,
            'progress_code'=> '',
            'fee_trans_id' => $model->trans_id
        ];

        $feeTransModel = $this->assign(new FeeTransactions(), $records);
        

        return $feeTransModel->save();
    }

    /**
     * update banking slips amount and reg_no
     *
     * @return boolean
     */
    public function updateBankingSlip(): bool
    {
        $beneficiary = SponsorBeneficiary::findOne($this->sponsor_beneficiary_id);
        $this->load(Yii::$app->request->post());
        return $beneficiary->updateBeneficiary() && $this->save();
    }
}
