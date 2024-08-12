<?php

namespace app\modules\examManagement\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.prog_curr_group_req_course".
 *
 * @property int $prog_curr_group_req_course_id
 * @property int $prog_curr_group_requirement_id
 * @property int $prog_curriculum_course_id
 * @property int $credit_factor
 */
class ProgCurrGroupReqCourse extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.prog_curr_group_req_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_course_id'], 'required'],
//            [['prog_curr_group_requirement_id', 'prog_curriculum_course_id', 'credit_factor'], 'default', 'value' => null],
            [['prog_curr_group_requirement_id', 'prog_curriculum_course_id', 'credit_factor'], 'integer'],
            [['prog_curriculum_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrCourse::class, 'targetAttribute' => ['prog_curriculum_course_id' => 'prog_curriculum_course_id']],
            [['prog_curr_group_requirement_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrGroupRequirement::class, 'targetAttribute' => ['prog_curr_group_requirement_id' => 'prog_curr_group_requirement_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_group_req_course_id' => 'Prog Curr Group Req Course ID',
            'prog_curr_group_requirement_id' => 'Prog Curr Group Requirement ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'credit_factor' => 'Credit Factor',
        ];
    }
}
