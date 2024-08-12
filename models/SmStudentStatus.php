<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_status".
 *
 * @property int $status_id
 * @property string $status
 * @property bool $current_status
 *
 * @property SmStudentProgrammeCurriculum[] $smStudentProgrammeCurriculums
 */
class SmStudentStatus extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'status', 'current_status'], 'required'],
            [['status_id'], 'default', 'value' => null],
            [['status_id'], 'integer'],
            [['current_status'], 'boolean'],
            [['status'], 'string', 'max' => 12],
            [['status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'status' => 'Status',
            'current_status' => 'Current Status',
        ];
    }

    /**
     * Gets query for [[SmStudentProgrammeCurriculums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentProgrammeCurriculums()
    {
        return $this->hasMany(SmStudentProgrammeCurriculum::class, ['status_id' => 'status_id']);
    }
}
