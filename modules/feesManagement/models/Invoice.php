<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_invoice".
 *
 * @property int $invoice_id
 * @property string|null $invoice_desc
 * @property string|null $invoice_date
 * @property string|null $user_id
 * @property string|null $last_update
 * @property string|null $invoice_status
 * @property float|null $amount
 * @property float|null $exchange_rate
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'required'],
            [['invoice_id'], 'default', 'value' => null],
            [['invoice_id'], 'integer'],
            [['invoice_date', 'last_update'], 'safe'],
            [['amount', 'exchange_rate'], 'number'],
            [['invoice_desc'], 'string', 'max' => 150],
            [['user_id', 'invoice_status'], 'string', 'max' => 30],
            [['invoice_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => 'Invoice ID',
            'invoice_desc' => 'Invoice Desc',
            'invoice_date' => 'Invoice Date',
            'user_id' => 'User ID',
            'last_update' => 'Last Update',
            'invoice_status' => 'Invoice Status',
            'amount' => 'Amount',
            'exchange_rate' => 'Exchange Rate',
        ];
    }
}
