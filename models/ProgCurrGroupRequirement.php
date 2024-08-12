<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.prog_curr_group_requirement".
 *
 * @property int $prog_curr_group_requirement_id
 * @property int $prog_curr_level_req_id
 * @property int $prog_curr_course_group_id
 * @property int $min_group_courses
 * @property string|null $group_pass_type
 * @property int $min_group_pass
 * @property string|null $gpa_pass_type
 * @property int $gpa_courses
 * @property string|null $extra_courses_processing
 */
class ProgCurrGroupRequirement extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.prog_curr_group_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curr_level_req_id', 'prog_curr_course_group_id'], 'required'],
            [['prog_curr_level_req_id', 'prog_curr_course_group_id', 'min_group_courses', 'min_group_pass', 'gpa_courses'], 'default', 'value' => null],
            [['prog_curr_level_req_id', 'prog_curr_course_group_id', 'min_group_courses', 'min_group_pass', 'gpa_courses'], 'integer'],
            [['group_pass_type', 'gpa_pass_type', 'extra_courses_processing'], 'string', 'max' => 4],
            [['prog_curr_course_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrCourseGroup::class, 'targetAttribute' => ['prog_curr_course_group_id' => 'course_group_id']],
            [['prog_curr_level_req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrLevelRequirement::class, 'targetAttribute' => ['prog_curr_level_req_id' => 'prog_curr_level_req_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_group_requirement_id' => 'Prog Curr Group Requirement ID',
            'prog_curr_level_req_id' => 'Prog Curr Level Req ID',
            'prog_curr_course_group_id' => 'Prog Curr Course Group ID',
            'min_group_courses' => 'Min Group Courses',
            'group_pass_type' => 'Group Pass Type',
            'min_group_pass' => 'Min Group Pass',
            'gpa_pass_type' => 'Gpa Pass Type',
            'gpa_courses' => 'Gpa Courses',
            'extra_courses_processing' => 'Extra Courses Processing',
        ];
    }

/**
     * Gets query for [[ProgCurrLevelRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrGroupReq()
    {
        return $this->hasMany(ProgCurrGroupRequirement::className(), ['prog_curr_group_requirement_id' => 'prog_curr_group_requirement_id']);
    }


/**
     * Gets query for [[ProgCurrLevelRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseGroupName()
    {
        return $this->hasMany(ProgCurrCourseGroup::className(), ['course_group_id' => 'prog_curr_course_group_id']);
    }

}
