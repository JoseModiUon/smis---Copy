<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_option_group_requirement".
 *
 * @property int $option_group_requirement_ID
 * @property int $fk_option_level_req_id references to prog_curr_option_level_requirement
 * @property int $option_course_group
 * @property int $min_courses
 * @property int|null $max_courses
 * @property string|null $pass_type
 * @property int|null $min_pass_courses
 * @property string|null $gpa_pass_type ALL,BEST
 * @property int|null $gpa_courses
 * @property string|null $extra_course_processing ALL, BEST
 *
 * @property ProgCurrOptionLevelRequirement $fkOptionLevelReq
 * @property ProgCurrOptionGroupCourse[] $progCurrOptionGroupCourses
 */
class ProgCurrOptionGroupRequirement extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prog_curr_option_group_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_option_level_req_id', 'option_course_group'], 'required'],
            [['fk_option_level_req_id', 'option_course_group', 'min_courses', 'max_courses', 'min_pass_courses', 'gpa_courses'], 'default', 'value' => null],
            [['fk_option_level_req_id', 'option_course_group', 'min_courses', 'max_courses', 'min_pass_courses', 'gpa_courses'], 'integer'],
            [['extra_course_processing'], 'string'],
            [['pass_type', 'gpa_pass_type'], 'string', 'max' => 5],
            [['fk_option_level_req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrOptionLevelRequirement::class, 'targetAttribute' => ['fk_option_level_req_id' => 'prog_curr_option_level_req_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'option_group_requirement_ID' => 'Option Group Requirement ID',
            'fk_option_level_req_id' => 'Fk Option Level Req ID',
            'option_course_group' => 'Option Course Group',
            'min_courses' => 'Min Courses',
            'max_courses' => 'Max Courses',
            'pass_type' => 'Pass Type',
            'min_pass_courses' => 'Min Pass Courses',
            'gpa_pass_type' => 'Gpa Pass Type',
            'gpa_courses' => 'Gpa Courses',
            'extra_course_processing' => 'Extra Course Processing',
        ];
    }

    /**
     * Gets query for [[FkOptionLevelReq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkOptionLevelReq()
    {
        return $this->hasOne(ProgCurrOptionLevelRequirement::class, ['prog_curr_option_level_req_id' => 'fk_option_level_req_id']);
    }

    /**
     * Gets query for [[ProgCurrOptionGroupCourses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrOptionGroupCourses()
    {
        return $this->hasMany(ProgCurrOptionGroupCourse::class, ['fk_option_group_req_id' => 'option_group_requirement_ID']);
    }
}
