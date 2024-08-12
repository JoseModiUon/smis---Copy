<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_billing_frequency".
 *
 * @property int $billing_frequency_id
 * @property string $name
 * @property string $description
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class BillingFrequency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_billing_frequency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['last_update'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
            [['userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'billing_frequency_id' => 'Billing Frequency ID',
            'name' => 'Name',
            'description' => 'Description',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
