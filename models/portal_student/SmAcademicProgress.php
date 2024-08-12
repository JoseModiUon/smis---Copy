<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_academic_progress".
 *
 * @property int $academic_progress_id
 * @property int $acad_session_id
 * @property int $academic_level_id
 * @property int $student_prog_curriculum_id how_the_student_acquired_the_status
 * @property int $progress_status_id
 * @property int $current_status
 *
 * @property OrgAcademicSession $acadSession
 * @property OrgAcademicLevels $academicLevel
 * @property SmAcademicProgressStatus $progressStatus
 * @property SmStudentSemSessionProgress[] $smStudentSemSessionProgresses
 * @property SmStudentProgrammeCurriculum $studentProgCurriculum
 */
class SmAcademicProgress extends \yii\db\ActiveRecord
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
        return 'smisportal.sm_academic_progress';
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
            [['academic_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgAcademicLevels::className(), 'targetAttribute' => ['academic_level_id' => 'academic_level_id']],
            [['acad_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgAcademicSession::className(), 'targetAttribute' => ['acad_session_id' => 'acad_session_id']],
            [['progress_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmAcademicProgressStatus::className(), 'targetAttribute' => ['progress_status_id' => 'progress_status_id']],
            [['student_prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentProgrammeCurriculum::className(), 'targetAttribute' => ['student_prog_curriculum_id' => 'student_prog_curriculum_id']],
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
        ];
    }

    /**
     * Gets query for [[AcadSession]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcadSession()
    {
        return $this->hasOne(OrgAcademicSession::className(), ['acad_session_id' => 'acad_session_id']);
    }

    /**
     * Gets query for [[AcademicLevel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicLevel()
    {
        return $this->hasOne(OrgAcademicLevels::className(), ['academic_level_id' => 'academic_level_id']);
    }

    /**
     * Gets query for [[ProgressStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgressStatus()
    {
        return $this->hasOne(SmAcademicProgressStatus::className(), ['progress_status_id' => 'progress_status_id']);
    }

    /**
     * Gets query for [[SmStudentSemSessionProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentSemSessionProgresses()
    {
        return $this->hasMany(SmStudentSemSessionProgress::className(), ['academic_progress_id' => 'academic_progress_id']);
    }

    /**
     * Gets query for [[StudentProgCurriculum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentProgCurriculum()
    {
        return $this->hasOne(SmStudentProgrammeCurriculum::className(), ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }
}
