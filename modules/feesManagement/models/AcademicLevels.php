<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.org_academic_levels".
 *
 * @property int $academic_level_id
 * @property int $academic_level
 * @property string $academic_level_name
 * @property int|null $academic_level_order
 * @property string $status
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AcademicLevels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_academic_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academic_level', 'academic_level_name'], 'required'],
            [['academic_level', 'academic_level_order'], 'default', 'value' => null],
            [['academic_level', 'academic_level_order'], 'integer'],
            [['last_update'], 'safe'],
            [['academic_level_name', 'userid'], 'string', 'max' => 20],
            [['status', 'user_action'], 'string', 'max' => 10],
            [['ip_address'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'academic_level_id' => 'Academic Level ID',
            'academic_level' => 'Academic Level',
            'academic_level_name' => 'Academic Level Name',
            'academic_level_order' => 'Academic Level Order',
            'status' => 'Status',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
