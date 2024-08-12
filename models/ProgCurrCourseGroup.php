<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_course_group".
 *
 * @property int $course_group_id
 * @property int $prog_curriculum_id
 * @property int $prog_study_level
 * @property int $min_courses_taken
 * @property string $pass_type
 * @property int $min_pass_courses
 * @property string $gpa_choice
 * 
 *
 * @property ProgCurrCourseGroup[] $ProgCurrCourseGroup
 */
//class OrgProgCurrOption extends \yii\db\ActiveRecord
class ProgCurrCourseGroup extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.prog_curr_course_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_group_name', 'course_group_desc', 'course_group_type'], 'required'],
            // [['course_group_id'], 'default', 'value' => null],
            // [['course_group_id'], 'integer'],
            [['course_group_name'], 'string'],
            [['course_group_desc'], 'string'],
            [['course_group_type'], 'string'],
            [['course_group_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_group_id' => 'Course Group ID',
            'course_group_name' => 'Course Group Name',
            'course_group_desc' => 'Course Group Desc',
            'course_group_type' => 'Course Group Type',
        ];
    }



    /**
     * Gets query for [[ProgCurrCourseGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrCourseGroup()
    {
        return $this->hasMany(ProgCurrCourseGroup::className(), ['course_group_id' => 'course_group_id']);
    }
    /**
     * Gets query for [[ProgCurrCourseGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    /*
    public function getProgrammeCurriculum()
    {
        return $this->hasMany(ProgCurrCourseGroup::className(), ['prog_curriculum_id' => 'prog_cur_id']);
    }*/
}
