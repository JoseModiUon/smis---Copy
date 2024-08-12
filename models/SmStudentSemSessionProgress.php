<?php

namespace app\models;

use Yii;
use app\models\SmAcademicProgress;
use app\models\traits\UserTracking;
use app\models\SmStudentSessionDetails;
use app\models\SmStudentSemesterSessionStatus;
///sss
/**
 * This is the model class for table "sm_student_sem_session_progress".
 *
 * @property int $student_semester_session_id
 * @property int|null $semester_progress
 * @property string $registration_date
 * @property int $academic_progress_id
 * @property int $sem_progress_number The student semester progression..ie 1,2,3
 * @property int|null $billable
 * @property string $promotion_status
 * @property int $rep_status_id
 *
 * @property SmAcademicProgress $academicProgress
 * @property SmStudentSemesterSessionStatus $reportingStatus
 * @property SmStudentSessionDetails[] $smStudentSessionDetails
 */
class SmStudentSemSessionProgress extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_sem_session_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semester_progress', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id'], 'default', 'value' => null],
            [['semester_progress', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id'], 'integer'],
            [['registration_date'], 'safe'],
            [['academic_progress_id', 'sem_progress_number', 'rep_status_id'], 'required'],
            [['promotion_status'], 'string', 'max' => 20],
            [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmAcademicProgress::className(), 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
            [['rep_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentSemesterSessionStatus::className(), 'targetAttribute' => ['rep_status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_semester_session_id' => 'Student Semester Session ID',
            'semester_progress' => 'Semester Progress',
            'registration_date' => 'Registration Date',
            'academic_progress_id' => 'Academic Progress ID',
            'sem_progress_number' => 'Sem Progress Number',
            'billable' => 'Billable',
            'promotion_status' => 'Promotion Status',
            'rep_status_id' => 'Reporting Status ID',
        ];
    }

    /**
     * Gets query for [[AcademicProgress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicProgress()
    {
        return $this->hasOne(SmAcademicProgress::className(), ['academic_progress_id' => 'academic_progress_id']);
    }

    /**
     * Gets query for [[ReportingStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReportingStatus()
    {
        return $this->hasOne(SmStudentSemesterSessionStatus::className(), ['status_id' => 'rep_status_id']);
    }

    /**
     * Gets query for [[SmStudentSessionDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentSessionDetails()
    {
        return $this->hasMany(SmStudentSessionDetails::className(), ['student_semester_session_id' => 'student_semester_session_id']);
    }

        /**
     * Gets query for [[SmStudentSessionDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseRegistration()
    {
        return $this->hasMany(CrCourseRegistration::className(), ['student_semester_session_id' => 'student_semester_session_id']);
    }
}
