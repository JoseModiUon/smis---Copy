<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_programme_curriculum".
 *
 * @property int $student_prog_curriculum_id
 * @property int $student_id
 * @property string $registration_number
 * @property int $prog_curriculum_id
 * @property int $student_category_id
 * @property int $adm_refno
 * @property int $status_id
 *
 * @property SmAdmittedStudent $admRefno
 * @property OrgProgrammeCurriculum $progCurriculum
 * @property SmAcademicProgress[] $smAcademicProgresses
 * @property SmStudentIdRequest[] $smStudentIdRequests
 * @property SmStudentRelations[] $smStudentRelations
 * @property SmStudentId[] $smStudents
 * @property SmStudentStatus $status
 * @property SmStudent $student
 * @property SmStudentCategory $studentCategory
 */
class SmStudentProgrammeCurriculum extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'default', 'value' => null],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['registration_number'], 'string', 'max' => 20],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => SmAdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudent::class, 'targetAttribute' => ['student_id' => 'student_id']],
            [['student_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentCategory::class, 'targetAttribute' => ['student_category_id' => 'std_category_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'student_category_id' => 'Student Category ID',
            'adm_refno' => 'Adm Refno',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * Gets query for [[AdmRefno]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmRefno()
    {
        return $this->hasOne(SmAdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculum()
    {
        return $this->hasOne(OrgProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * Gets query for [[SmAcademicProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAcademicProgresses()
    {
        return $this->hasMany(SmAcademicProgress::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }

    /**
     * Gets query for [[SmStudentIdRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentIdRequests()
    {
        return $this->hasMany(SmStudentIdRequest::class, ['student_prog_curr_id' => 'student_prog_curriculum_id']);
    }

    /**
     * Gets query for [[SmStudentRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentRelations()
    {
        return $this->hasMany(SmStudentRelations::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }

    /**
     * Gets query for [[SmStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudents()
    {
        return $this->hasMany(SmStudentId::class, ['student_prog_curr_id' => 'student_prog_curriculum_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(SmStudentStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(SmStudent::class, ['student_id' => 'student_id']);
    }

    /**
     * Gets query for [[StudentCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentCategory()
    {
        return $this->hasOne(SmStudentCategory::class, ['std_category_id' => 'student_category_id']);
    }
}
