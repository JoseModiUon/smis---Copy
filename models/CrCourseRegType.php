<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "cr_course_reg_type".
 *
 * @property int $course_reg_type_id
 * @property string $course_reg_type_code FA,SUPP,RETAKE
 * @property string|null $course_reg_type_name FIRST ATTEMPT, SUPPLIMENTARY,RETAKE
 * @property string|null $course_reg_entry_type ORIGINAL,PASSMARK
 *
 * @property CrCourseRegistration[] $crCourseRegistrations
 */
class CrCourseRegType extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_course_reg_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_reg_type_id', 'course_reg_type_code'], 'required'],
            [['course_reg_type_id'], 'default', 'value' => null],
            [['course_reg_type_id'], 'integer'],
            [['course_reg_type_code'], 'string', 'max' => 10],
            [['course_reg_type_name', 'course_reg_entry_type'], 'string', 'max' => 20],
            [['course_reg_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_reg_type_id' => 'Course Reg Type ID',
            'course_reg_type_code' => 'Course Reg Type Code',
            'course_reg_type_name' => 'Course Reg Type Name',
            'course_reg_entry_type' => 'Course Reg Entry Type',
        ];
    }

    /**
     * Gets query for [[CrCourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCrCourseRegistrations()
    {
        return $this->hasMany(CrCourseRegistration::class, ['course_registration_type_id' => 'course_reg_type_id']);
    }
}
