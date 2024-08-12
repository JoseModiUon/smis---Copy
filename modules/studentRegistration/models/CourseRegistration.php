<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:10 AM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
/**
 * This is the model class for table "smis.cr_course_registration".
 *
 * @property int $student_course_reg_id
 * @property int $timetable_id
 * @property int $student_semester_session_id
 * @property int $course_registration_type_id
 * @property string $registration_date
 * @property int $course_reg_status_id
 * @property string|null $source_ipaddress
 * @property string|null $userid
 * @property int|null $class_code
 * @property bool|null $sync_status
 */
class CourseRegistration extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.cr_course_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id'], 'required'],
            [['timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id', 'class_code'], 'default', 'value' => null],
            [['timetable_id', 'student_semester_session_id', 'course_registration_type_id', 'course_reg_status_id', 'class_code'], 'integer'],
            [['registration_date'], 'safe'],
//            [['sync_status'], 'boolean'],
            [['source_ipaddress'], 'string', 'max' => 100],
            [['userid'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
            'class_code' => 'Class Code',
            'sync_status' => 'Sync Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMarksheet(): ActiveQuery
    {
        return $this->hasOne(Marksheet::class, ['student_course_reg_id' => 'student_course_reg_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(CourseRegistrationStatus::class, ['course_reg_status_id' => 'course_reg_status_id']);
    }
}
