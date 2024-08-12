<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "cr_course_registration".
 *
 * @property int $student_course_reg_id
 * @property int $timetable_id
 * @property int $student_semester_session_id
 * @property int $course_registration_type_id
 * @property string $registration_date
 * @property int $course_reg_status_id
 * @property string|null $source_ipaddress
 * @property string|null $userid
 *
 * @property CrCourseRegStatus $courseRegStatus
 * @property CrCourseRegType $courseRegistrationType
 * @property ExMarksheet[] $exMarksheets
 * @property CrProgCurrTimetable $timetable
 */
class CrCourseRegistration extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.cr_course_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_course_reg_id', 'timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id'], 'required'],
            [['student_course_reg_id', 'timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id'], 'default', 'value' => null],
            [['student_course_reg_id', 'timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id'], 'integer'],
            [['registration_date'], 'safe'],
            [['source_ipaddress'], 'string', 'max' => 100],
            [['userid'], 'string', 'max' => 30],
            [['student_course_reg_id'], 'unique'],
            [['course_reg_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrCourseRegStatus::class, 'targetAttribute' => ['course_reg_status_id' => 'course_reg_status_id']],
            [['course_registration_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrCourseRegType::class, 'targetAttribute' => ['course_registration_type_id' => 'course_reg_type_id']],
            [['timetable_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrProgCurrTimetable::class, 'targetAttribute' => ['timetable_id' => 'timetable_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_course_reg_id' => 'Student Course Reg ID',
            'timetable_id' => 'Timetable ID',
            'student_semester_session_id' => 'Student Semester Session ID',
            'course_registration_type_id' => 'Course Registration Type ID',
            'registration_date' => 'Registration Date',
            'course_reg_status_id' => 'Course Reg Status ID',
            'source_ipaddress' => 'Source Ipaddress',
            'userid' => 'Userid',
        ];
    }

    /**
     * Gets query for [[CourseRegStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseRegStatus()
    {
        return $this->hasOne(CrCourseRegStatus::class, ['course_reg_status_id' => 'course_reg_status_id']);
    }

    /**
     * Gets query for [[CourseRegistrationType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseRegistrationType()
    {
        return $this->hasOne(CrCourseRegType::class, ['course_reg_type_id' => 'course_registration_type_id']);
    }

    /**
     * Gets query for [[ExMarksheets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExMarksheets()
    {
        return $this->hasMany(ExMarksheet::class, ['student_course_reg_id' => 'student_course_reg_id']);
    }

    /**
     * Gets query for [[Timetable]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetable()
    {
        return $this->hasOne(CrProgCurrTimetable::class, ['timetable_id' => 'timetable_id']);
    }

    /**
     * Gets query for [[classGroups]]
     */
    public function getClassGroups()
    {
        return $this->hasOne(CrClassGroups::class, ['class_code' => 'class_code']);
    }


    /**
     * Gets query for [[classGroups]]
     */
    public function getStudent()
    {
        return $this->hasOne(SmStudent::class, ['student_number' => 'userid']);
    }

    /**
     * Gets query for [[classGroups]]
     */
    public function getSmStudentSemSessionProgress()
    {
        return $this->hasOne(SmStudentSemSessionProgress::class, ['student_semester_session_id' => 'student_semester_session_id']);
    }
}
