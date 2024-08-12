<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_curr_course".
 *
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_id
 * @property int $course_id
 * @property int $credit_factor
 * @property float $credit_hours
 * @property int $level_of_study
 * @property int|null $semester
// * @property int|null $prerequisite
 * @property string $status
 * @property bool|null $has_course_work
 *
 * @property OrgCourses $course
 * @property OrgProgrammeCurriculum $progCurriculum
 */
class OrgProgCurrCourse extends \yii\db\ActiveRecord
{
    use UserTracking;


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_prog_curr_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'course_id', 'credit_hours', 'level_of_study'], 'required'],
            [['prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester',], 'default', 'value' => null],
            [['prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester',], 'integer'],
            [['credit_hours'], 'number'],
            [['has_course_work'], 'boolean'],
            [['status'], 'string', 'max' => 10],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgCourses::className(), 'targetAttribute' => ['course_id' => 'course_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgrammeCurriculum::className(), 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'course_id' => 'Course ID',
            'credit_factor' => 'Credit Factor',
            'credit_hours' => 'Credit Hours',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
            //            'prerequisite' => 'Prerequisite',
            'status' => 'Status',
            'has_course_work' => 'Has Course Work',
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(OrgCourses::className(), ['course_id' => 'course_id']);
    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculum()
    {
        return $this->hasOne(OrgProgrammeCurriculum::className(), ['prog_curriculum_id' => 'prog_curriculum_id']);
    }
    public function getAcademicLevels()
    {
        return $this->hasOne(OrgAcademicLevels::className(), ['academic_level_id' => 'level_of_study']);
    }
    public function getCoursePrerequisite()
    {
        return $this->hasMany(OrgCoursePrerequisite::className(), ['prog_curriculum_course_id' => 'prog_curriculum_course_id']);
    }

    public function getSemesterCode()
    {
        return $this->hasOne(OrgSemesterCode::class, ['semester_code' => 'semester']);
    }
}
