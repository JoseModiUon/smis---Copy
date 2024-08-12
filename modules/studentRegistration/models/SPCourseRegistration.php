<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/11/2023
 * @time: 5:42 PM
 */

namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

class SPCourseRegistration extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_course_registration';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db2');
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
            [['sync_status'], 'boolean'],
            [['source_ipaddress'], 'string', 'max' => 100],
            [['userid'], 'string', 'max' => 30],
            [['registration_number'], 'string', 'max' => 20],
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
            'registration_number' => 'Registration Number',
        ];
    }
}
