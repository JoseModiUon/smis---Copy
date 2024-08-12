<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_receipt_information".
 *
 * @property int $receipt_info_id
 * @property int|null $trans_id
 * @property string|null $trans_date
 * @property string|null $student_regno
 * @property string|null $reverse_approval
 * @property string|null $allow_duplicate
 * @property float|null $amount
 * @property string|null $last_update
 * @property string|null $user_id
 * @property string|null $ip_address
 */
class FssReceiptInformation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_receipt_information';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_info_id', 'trans_id'], 'integer'],
            [['trans_date', 'last_update'], 'safe'],
            [['amount'], 'number'],
            [['student_regno'], 'string', 'max' => 30],
            [['reverse_approval', 'allow_duplicate'], 'string', 'max' => 3],
            [['user_id'], 'string', 'max' => 15],
            [['ip_address'], 'string', 'max' => 25],
            [['receipt_info_id'], 'unique'],
            [['trans_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankingSlips::class, 'targetAttribute' => ['trans_id' => 'trans_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'receipt_info_id' => 'Receipt Info ID',
            'trans_id' => 'Trans ID',
            'trans_date' => 'Trans Date',
            'student_regno' => 'Student Regno',
            'reverse_approval' => 'Reverse Approval',
            'allow_duplicate' => 'Allow Duplicate',
            'amount' => 'Amount',
            'last_update' => 'Last Update',
            'user_id' => 'User ID',
            'ip_address' => 'Ip Address',
        ];
    }
}
