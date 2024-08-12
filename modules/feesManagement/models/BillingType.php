<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_billing_type".
 *
 * @property int $billing_type_id
 * @property string|null $billing_type_desc
 */
class BillingType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_billing_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_type_desc'], 'string', 'max' => 25],
            [['billing_type_desc'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'billing_type_id' => 'Billing Type ID',
            'billing_type_desc' => 'Billing Type Desc',
        ];
    }
}
