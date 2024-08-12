<?php

namespace app\models;


use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "smis.fss_sponsor_beneficiary".
 *
 * @property int $sponsor_beneficiary_id
 * @property int $receipt_sponsor_fund_id
 * @property string $reg_no
 * @property string|null $name
 * @property string|null $trans_type
 * @property string|null $transfer_status
 * @property float $amount
 * @property string $post_date
 * @property string $user_id
 */
class SponsorBeneficiary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_sponsor_beneficiary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_sponsor_fund_id', 'reg_no', 'amount', 'post_date', 'user_id'], 'required'],
            [['receipt_sponsor_fund_id'], 'default', 'value' => null],
            [['receipt_sponsor_fund_id'], 'integer'],
            [['amount'], 'number'],
            [['post_date'], 'safe'],
            [['user_id'], 'string'],
            [['reg_no', 'trans_type', 'transfer_status'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 30],
            [['receipt_sponsor_fund_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReceiptSponsorFund::class, 'targetAttribute' => ['receipt_sponsor_fund_id' => 'receipt_sponsor_fund_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_beneficiary_id' => 'Sponsor Beneficiary ID',
            'receipt_sponsor_fund_id' => 'Receipt Sponsor Fund ID',
            'reg_no' => 'Reg No',
            'name' => 'Name',
            'trans_type' => 'Trans Type',
            'transfer_status' => 'Transfer Status',
            'amount' => 'Amount',
            'post_date' => 'Post Date',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Find the running balance at the interval of every beneficiary
     *
     * @return integer
     */
    public function getRunningBalance() :int
    {
        $allFunds = $this->find()->where(['receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id])->orderBy(['sponsor_beneficiary_id' => SORT_ASC])->asArray()->all();
        $data = ArrayHelper::map($allFunds, 'sponsor_beneficiary_id', 'amount');
        $totalAllocated = 0;
        foreach ($data as $sponsor_beneficiary_id => $amount) {
            if ($sponsor_beneficiary_id <= $this->sponsor_beneficiary_id) {
                $totalAllocated += $amount;
            }
        }

        $totalAmount = ReceiptSponsorFund::findOne($this->receipt_sponsor_fund_id)->amount;

        return $totalAmount - $totalAllocated;

    }
}
