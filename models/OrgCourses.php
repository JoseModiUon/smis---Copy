<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_courses".
 *
 * @property int $course_id
 * @property string $course_code
 * @property string $course_name
 * @property int $level_of_study
 * @property int $semester
 * @property int $academic_hours
 * @property string $status
 * @property int $org_unit_id
 * @property int|null $billing_factor
 * @property int $category_id
 *
 * @property CrCourseCategory $category
 * @property OrgProgCurrCourse[] $orgProgCurrCourses
 * @property OrgUnitCourse[] $orgUnitCourses
 */
class OrgCourses extends \yii\db\ActiveRecord
{
    use UserTracking;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_code', 'course_name', 'level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'category_id'], 'required'],
            [['level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'billing_factor', 'category_id'], 'default', 'value' => null],
            [['level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'billing_factor', 'category_id'], 'integer'],
            [['course_code'], 'string', 'max' => 10],
            [['course_name'], 'string', 'max' => 150],
            [['status'], 'string', 'max' => 10],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrCourseCategory::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_id' => 'Course ID',
            'course_code' => 'Course Code',
            'course_name' => 'Course Name',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
            'academic_hours' => 'Academic Hours',
            'status' => 'Status',
            'org_unit_id' => 'Org Unit ID',
            'billing_factor' => 'Billing Factor',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CrCourseCategory::className(), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[OrgProgCurrCourses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrCourses()
    {
        return $this->hasMany(OrgProgCurrCourse::className(), ['course_id' => 'course_id']);
    }

    /**
     * Gets query for [[OrgUnitCourses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgUnitCourses()
    {
        return $this->hasMany(OrgUnitCourse::className(), ['course_id' => 'course_id']);
    }
    public function getOrgUnit()
    {
        return $this->hasOne(OrgUnit::className(), ['unit_id' => 'org_unit_id']);
    }
    public function getAcademicLevels()
    {
        return $this->hasOne(OrgAcademicLevels::className(), ['academic_level' => 'level_of_study']);
    }
    public function getPrerequisite()
    {
        return $this->hasMany(OrgCoursePrerequisite::className(), ['course_id' => 'course_id']);
    }

    public function getSemesterCode()
    {
        return $this->hasOne(OrgSemesterCode::class, ['semester_code' => 'semester']);
    }
}
