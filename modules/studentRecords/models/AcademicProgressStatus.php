<?php

namespace app\modules\studentRecords\models;

use Yii;

/**
 * This is the model class for table "smis.sm_academic_progress_status".
 *
 * @property int $progress_status_id
 * @property string $progress_status_name
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AcademicProgressStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_academic_progress_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['progress_status_name'], 'required'],
            [['last_update'], 'safe'],
            [['progress_status_name'], 'string', 'max' => 150],
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
            'progress_status_id' => 'Progress Status ID',
            'progress_status_name' => 'Progress Status Name',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
