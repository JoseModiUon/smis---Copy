<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_option_course_group".
 *
 * @property int $course_group_id
 * @property string $course_group_name
 * @property string $course_group_desc
 * @property string $course_group_type COMPULSORY, OPTIONAL
 */
class ProgCurrOptionCourseGroup extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prog_curr_option_course_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_group_name', 'course_group_desc', 'course_group_type'], 'required'],
            [['course_group_name'], 'string', 'max' => 30],
            [['course_group_desc'], 'string', 'max' => 50],
            [['course_group_type'], 'string', 'max' => 12],
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
}
