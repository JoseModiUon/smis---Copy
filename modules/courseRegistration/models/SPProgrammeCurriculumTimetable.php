<?php

namespace app\modules\courseRegistration\models;

use app\models\traits\UserTracking;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smisportal.cr_prog_curr_timetable".
 *
 * @property int $timetable_id
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_sem_group_id
 * @property string|null $exam_date
 * @property int|null $exam_venue
 * @property int $exam_mode
 * @property string|null $mrksheet_id
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class SPProgrammeCurriculumTimetable extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_prog_curr_timetable';
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
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_mode'], 'required'],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'default', 'value' => null],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [['exam_date', 'last_update'], 'safe'],
            [['mrksheet_id'], 'string', 'max' => 100],
            [['userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
            [['timetable_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'timetable_id' => 'Timetable ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'exam_date' => 'Exam Date',
            'exam_venue' => 'Exam Venue',
            'exam_mode' => 'Exam Mode',
            'mrksheet_id' => 'Mrksheet ID',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
