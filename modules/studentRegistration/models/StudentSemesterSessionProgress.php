<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_student_sem_session_progress".
 *
 * @property int $student_semester_session_id
 * @property string|null $registration_date
 * @property int $academic_progress_id
 * @property int $sem_progress_number The student semester progression..ie 1,2,3
 * @property int|null $billable
 * @property int $rep_status_id
 * @property int $prom_status_id
 * @property bool|null $reporting_snyc_status
 * @property int|null $semester_progress
 * @property string|null $promotion_status
 * @property int $prog_curriculum_semester_id
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class StudentSemesterSessionProgress extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_sem_session_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['registration_date', 'last_update'], 'safe'],
            [['academic_progress_id', 'sem_progress_number', 'rep_status_id', 'prom_status_id', 'prog_curriculum_semester_id'], 'required'],
            [['academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id', 'semester_progress', 'prog_curriculum_semester_id'], 'default', 'value' => null],
            [['academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id', 'semester_progress', 'prog_curriculum_semester_id'], 'integer'],
            [['reporting_snyc_status'], 'boolean'],
            [['promotion_status', 'userid'], 'string', 'max' => 20],
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
            'student_semester_session_id' => 'Student Semester Session ID',
            'registration_date' => 'Registration Date',
            'academic_progress_id' => 'Academic Progress ID',
            'sem_progress_number' => 'Sem Progress Number',
            'billable' => 'Billable',
            'rep_status_id' => 'Rep Status ID',
            'prom_status_id' => 'Prom Status ID',
            'reporting_snyc_status' => 'Reporting Snyc Status',
            'semester_progress' => 'Semester Progress',
            'promotion_status' => 'Promotion Status',
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicProgress(): ActiveQuery
    {
        return $this->hasOne(AcademicProgress::class, ['academic_progress_id' => 'academic_progress_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentSemesterSessionStatus(): ActiveQuery
    {
        return $this->hasOne(StudentSemesterSessionStatus::class, ['status_id' => 'rep_status_id']);
    }
}
