<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_payment_types".
 *
 * @property int $payment_type_id
 * @property string|null $payment_desc
 * @property string|null $entry_type
 * @property string|null $payment_frequency
 * @property string|null $type_postfix
 * @property int|null $order_priority
 */
class FssPaymentTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_payment_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_type_id'], 'required'],
            [['payment_type_id', 'order_priority'], 'default', 'value' => null],
            [['payment_type_id', 'order_priority'], 'integer'],
            [['payment_desc'], 'string', 'max' => 150],
            [['entry_type', 'payment_frequency', 'type_postfix'], 'string', 'max' => 25],
            [['payment_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_type_id' => 'Payment Type ID',
            'payment_desc' => 'Payment Desc',
            'entry_type' => 'Entry Type',
            'payment_frequency' => 'Payment Frequency',
            'type_postfix' => 'Type Postfix',
            'order_priority' => 'Order Priority',
        ];
    }
}
