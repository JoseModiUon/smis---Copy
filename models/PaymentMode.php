<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smis.fss_payment_modes".
 *
 * @property string|null $mode_code
 * @property string|null $description
 * @property float|null $mode_flag
 * @property int $payment_mode_id
 */
class PaymentMode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_payment_modes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode_flag'], 'number'],
            [['mode_code'], 'string', 'max' => 40],
            [['description'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mode_code' => 'Mode Code',
            'description' => 'Description',
            'mode_flag' => 'Mode Flag',
            'payment_mode_id' => 'Payment Mode ID',
        ];
    }
}
