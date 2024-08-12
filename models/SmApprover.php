<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_approver".
 *
 * @property int $approver_id
 * @property string $approver
 * @property int $level
 * @property string $status
 */
class SmApprover extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_approver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approver_id', 'approver', 'level', 'status'], 'required'],
            [['approver_id', 'level'], 'default', 'value' => null],
            [['approver_id', 'level'], 'integer'],
            [['approver', 'status'], 'string'],
            [['approver_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approver_id' => 'Approver ID',
            'approver' => 'Approver',
            'level' => 'Level',
            'status' => 'Status',
        ];
    }
}
