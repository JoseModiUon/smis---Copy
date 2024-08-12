<?php

namespace app\modules\studentRecords\models;

use Yii;

/**
 * This is the model class for table "smis.org_academic_session".
 *
 * @property int $acad_session_id
 * @property string $acad_session_name
 * @property string|null $acad_session_desc
 * @property string $start_date
 * @property string $end_date
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AcademicSession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_academic_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_name', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date', 'last_update'], 'safe'],
            [['acad_session_name', 'ip_address'], 'string', 'max' => 50],
            [['acad_session_desc'], 'string', 'max' => 150],
            [['userid'], 'string', 'max' => 20],
            [['user_action'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acad_session_id' => 'Acad Session ID',
            'acad_session_name' => 'Acad Session Name',
            'acad_session_desc' => 'Acad Session Desc',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
