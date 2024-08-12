<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_option_group_requirement".
 *
 * @property int $prog_curr_level_req_id
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
 * @property ProgCurrGroupRequirement[] $ProgCurrGroupRequirement
 */
//class OrgProgCurrOption extends \yii\db\ActiveRecord
class ProgCurrGroupRequirement extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.prog_curr_option_group_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_option_level_req_id', 'option_course_group', 'min_courses'], 'required'],
            // [['prog_curr_level_req_id'], 'default', 'value' => null],
            // [['prog_curr_level_req_id'], 'integer'],
            [['fk_option_level_req_id'], 'integer'],
            [['option_course_group'], 'integer'],
            [['min_courses'], 'integer'],
            [['max_courses'], 'integer'],
            [['pass_type'], 'string'],
            [['min_pass_courses'], 'integer'],
            [['gpa_pass_type'], 'integer'],
            [['gpa_courses'], 'string'],
            [['extra_course_processing'], 'string'],
            [['option_group_requirement_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'option_group_requirement_ID' => 'Option Group Requirement ID',
            'fk_option_level_req_id' => 'fk option level req id',
            'option_course_group' => 'option course group',
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
     * Gets query for [[ProgCurrGroupRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrGroupRequirement()
    {
        return $this->hasMany(ProgCurrGroupRequirement::className(), ['prog_curr_level_req_id' => 'prog_curr_level_req_id']);
    }
    /**
     * Gets query for [[ProgCurrGroupRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCurriculum()
    {
        return $this->hasMany(ProgCurrGroupRequirement::className(), ['prog_curriculum_id' => 'prog_cur_id']);
    }
}
