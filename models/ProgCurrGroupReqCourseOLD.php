<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_group_req_course".
 *
 * @property int $prog_curr_group_req_course_id
 * @property int $prog_curriculum_id
 * @property int $prog_study_level
 * @property int $min_courses_taken
 * @property string $pass_type
 * @property int $min_pass_courses
 * @property string $gpa_choice
 * @property int $gpa_courses
 * @property int $gpa_weight
 * @property string $pass_result
 * @property string $pass_recommendation
 * @property string $fail_type
 * @property string $fail_result
 * @property string $fail_recommendation
 * @property string $incomplete_result
 * @property string $incomplete_recommendation
 *
 *
 * @property ProgCurrGroupReqCourse[] $ProgCurrGroupReqCourse
 */
//class OrgProgCurrOption extends \yii\db\ActiveRecord
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
            [['prog_curr_group_requirement_id', 'prog_curriculum_course_id', 'credit_factor'], 'required'],
            // [['prog_curr_group_req_course_id'], 'default', 'value' => null],
            // [['prog_curr_group_req_course_id'], 'integer'],
            [['prog_curr_group_requirement_id'], 'integer'],
            [['prog_curriculum_course_id'], 'integer'],
            [['credit_factor'], 'integer'],
            [['prog_curr_group_req_course_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_group_req_course_id' => 'Prog Curr Group Req Course ID',
            'prog_curr_group_requirement_id' => 'Prog Curr Group Req ID',
            'prog_curriculum_course_id' => 'Prog Curr Group Req Course ID',
            'credit_factor' => 'Credit Factor',
        ];
    }



    /**
     * Gets query for [[ProgCurrGroupReqCourse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrGroupReqCourse()
    {
        return $this->hasMany(ProgCurrGroupReqCourse::className(), ['prog_curr_group_req_course_id' => 'prog_curr_group_req_course_id']);
    }
    /**
     * Gets query for [[getProgCurrGroupRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrGroupRequirement()
    {
        return $this->hasMany(ProgCurrGroupReqCourse::className(), ['prog_curr_group_requirement_id' => 'prog_curr_group_requirement_id']);
    }

    /**
    * Gets query for [[getProgCurrGroupRequirement]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getProgCurrCourseId()
    {
        return $this->hasMany(ProgCurrGroupReqCourse::className(), ['prog_curriculum_course_id' => 'prog_curriculum_course_id']);
    }
}
