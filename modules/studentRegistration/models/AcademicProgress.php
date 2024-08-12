<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_academic_progress".
 *
 * @property int $academic_progress_id
 * @property int $acad_session_id
 * @property int $academic_level_id
 * @property int $student_prog_curriculum_id how_the_student_acquired_the_status
 * @property int $progress_status_id
 * @property int $current_status
 */
class AcademicProgress extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_academic_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'required'],
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'default', 'value' => null],
            [['acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'], 'integer'],
            [['academic_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicLevel::class, 'targetAttribute' => ['academic_level_id' => 'academic_level_id']],
            [['progress_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicProgressStatus::class, 'targetAttribute' => ['progress_status_id' => 'progress_status_id']],
            [['student_prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentProgCurriculum::class, 'targetAttribute' => ['student_prog_curriculum_id' => 'student_prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'academic_progress_id' => 'Academic Progress ID',
            'acad_session_id' => 'Acad Session ID',
            'academic_level_id' => 'Academic Level ID',
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'progress_status_id' => 'Progress Status ID',
            'current_status' => 'Current Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicLevel(): ActiveQuery
    {
        return $this->hasOne(AcademicLevel::class, ['academic_level_id' => 'academic_level_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicProgressStatus(): ActiveQuery
    {
        return $this->hasOne(AcademicProgressStatus::class, ['progress_status_id' => 'progress_status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(StudentProgCurriculum::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }
}
