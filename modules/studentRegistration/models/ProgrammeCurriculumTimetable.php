<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.cr_prog_curr_timetable".
 *
 * @property int $timetable_id
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_sem_group_id
 * @property string|null $exam_date
 * @property int|null $exam_venue
 * @property int $exam_mode
 * @property string $mrksheet_id
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class ProgrammeCurriculumTimetable extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.cr_prog_curr_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_mode'], 'required'],
            [['prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'default', 'value' => null],
            [['prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [['exam_date'], 'safe'],
            [['mrksheet_id'], 'safe'],
            [['userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
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
            'mrksheet_id' => 'Mrksheet id',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getExamMode(): ActiveQuery
    {
        return $this->hasOne(ExamMode::class, ['exam_mode_id' => 'exam_mode']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculumSemesterGroup(): ActiveQuery
    {
        return $this->hasOne(ProgCurrSemesterGroup::class, ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculumCourse(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculumCourse::class, ['prog_curriculum_course_id' => 'prog_curriculum_course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourseRegistration(): ActiveQuery
    {
        return $this->hasOne(CourseRegistration::class, ['timetable_id' => 'timetable_id']);
    }
}
