<?php

namespace app\modules\studentRegistration\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_student_sponsor".
 *
 * @property int $sponsor_id
 * @property string $sponsor_code
 * @property string $sponsor_name
 * @property string $status
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class StudentSponsor extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_code', 'sponsor_name'], 'required'],
            [['last_update'], 'safe'],
            [['sponsor_code', 'status', 'user_action'], 'string', 'max' => 10],
            [['sponsor_name', 'ip_address'], 'string', 'max' => 50],
            [['userid'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_id' => 'Sponsor ID',
            'sponsor_code' => 'Sponsor Code',
            'sponsor_name' => 'Sponsor Name',
            'status' => 'Status',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
