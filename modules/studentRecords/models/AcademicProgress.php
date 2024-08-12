<?php

namespace app\modules\studentRecords\models;

use app\models\StudentProgrammeCurriculum;
use app\modules\studentRegistration\models\AcademicLevel;
use Yii;

/**
 * This is the model class for table "smis.sm_academic_progress".
 *
 * @property int $academic_progress_id
 * @property int $acad_session_id
 * @property int $academic_level_id
 * @property int $student_prog_curriculum_id how_the_student_acquired_the_status
 * @property int $progress_status_id
 * @property int $current_status
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AcademicProgress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_academic_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'required'],
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'default', 'value' => null],
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'integer'],
            [['last_update'], 'safe'],
            [['userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
            [['academic_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicLevel::class, 'targetAttribute' => ['academic_level_id' => 'academic_level_id']],
            [['progress_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicProgressStatus::class, 'targetAttribute' => ['progress_status_id' => 'progress_status_id']],
            [['student_prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentProgrammeCurriculum::class, 'targetAttribute' => ['student_prog_curriculum_id' => 'student_prog_curriculum_id']],
            [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentSemSessionProgress::class, 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
            [['acad_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicSession::class, 'targetAttribute' => ['acad_session_id' => 'acad_session_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'academic_progress_id' => 'Academic Progress ID',
            'acad_session_id' => 'Acad Session ID',
            'academic_level_id' => 'Academic Level ID',
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'progress_status_id' => 'Progress Status ID',
            'current_status' => 'Current Status',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    public function getAcademicLevel(){
        return $this->hasOne(AcademicLevel::class, ['academic_level_id' => 'academic_level_id']);        
    }

    public function getAcademicProgressStatus(){
        return $this->hasOne(AcademicProgressStatus::class, ['progress_status_id' => 'progress_status_id']);        
    }
    
    public function getStudentProgrammeCurriculum(){
        return $this->hasOne(StudentProgrammeCurriculum::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);        
    }

    public function getStudentSemSessionProgress(){
        return $this->hasOne(StudentSemSessionProgress::class, ['academic_progress_id' => 'academic_progress_id']);        
    }

    public function getAcademicSession(){
        return $this->hasOne(AcademicSession::class, ['acad_session_id' => 'acad_session_id']);        
    }


}
