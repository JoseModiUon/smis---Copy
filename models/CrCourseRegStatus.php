<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "cr_course_reg_status".
 *
 * @property int $course_reg_status_id
 * @property string $course_reg_status_name
 *
 * @property CrCourseRegistration[] $crCourseRegistrations
 */
class CrCourseRegStatus extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_course_reg_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_reg_status_id', 'course_reg_status_name'], 'required'],
            [['course_reg_status_id'], 'default', 'value' => null],
            [['course_reg_status_id'], 'integer'],
            [['course_reg_status_name'], 'string'],
            [['course_reg_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_reg_status_id' => 'Course Reg Status ID',
            'course_reg_status_name' => 'Course Reg Status Name',
        ];
    }

    /**
     * Gets query for [[CrCourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrCourseRegistrations()
    {
        return $this->hasMany(CrCourseRegistration::class, ['course_reg_status_id' => 'course_reg_status_id']);
    }
}
