<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_prog_curr_status".
 *
 * @property int $student_prog_curr_stat_id
 * @property int $student_programme_curriculum_id
 * @property int $student_status_id
 */
class SmStudentProgCurrStatus extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_prog_curr_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_prog_curr_stat_id', 'student_programme_curriculum_id', 'student_status_id'], 'required'],
            [['student_prog_curr_stat_id', 'student_programme_curriculum_id', 'student_status_id'], 'default', 'value' => null],
            [['student_prog_curr_stat_id', 'student_programme_curriculum_id', 'student_status_id'], 'integer'],
            [['student_prog_curr_stat_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_prog_curr_stat_id' => 'Student Prog Curr Stat ID',
            'student_programme_curriculum_id' => 'Student Programme Curriculum ID',
            'student_status_id' => 'Student Status ID',
        ];
    }
}
