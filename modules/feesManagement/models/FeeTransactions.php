<?php

namespace app\modules\feesManagement\models;

use app\models\SmAcademicProgress;
use Yii;

/**
 * This is the model class for table "smis.fss_fee_transactions".
 *
 * @property int $trans_id
 * @property int $academic_progress_id
 * @property string $trans_date
 * @property string $trans_type
 * @property float $trans_amount
 * @property string|null $trans_desc
 * @property string $user_id
 * @property string|null $receipt_status
 * @property float|null $exchange_rate
 * @property int|null $invoice_id
 */
class FeeTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_fee_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academic_progress_id', 'trans_date', 'trans_type', 'trans_amount', 'user_id'], 'required'],
            [['academic_progress_id'], 'default', 'value' => null],
            [['trans_id', 'academic_progress_id'], 'integer'],
            [['trans_date'], 'safe'],
            [['trans_amount', 'exchange_rate'], 'number'],
            [['trans_type'], 'string', 'max' => 25],
            [['trans_desc'], 'string', 'max' => 150],
            [['user_id'], 'string', 'max' => 30],
            [['receipt_status'], 'string', 'max' => 15],
            [['trans_id'], 'unique'],
            // [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoice_id' => 'invoice_id']],
            [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmAcademicProgress::class, 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trans_id' => 'Trans ID',
            'academic_progress_id' => 'Academic Progress ID',
            'trans_date' => 'Trans Date',
            'trans_type' => 'Trans Type',
            'trans_amount' => 'Trans Amount',
            'trans_desc' => 'Trans Desc',
            'user_id' => 'User ID',
            'receipt_status' => 'Receipt Status',
            'exchange_rate' => 'Exchange Rate',
            // 'invoice_id' => 'Invoice ID',
        ];
    }


    public function getBankingSlips() {
        return $this->hasOne(BankingSlips::class, ['trans_id' => 'trans_id']);
    }
}
