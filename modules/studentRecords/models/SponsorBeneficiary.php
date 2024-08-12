<?php

namespace app\modules\studentRecords\models;

use app\models\ReceiptSponsorFund;
use Yii;

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
 * @property string|null $file_path
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
            [['file_path'], 'string', 'max' => 255],
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
            'file_path' => 'File Path',
        ];
    }


    public function getBeneficiaryId()
    {
        return $this->sponsor_beneficiary_id;
    }

    
}
