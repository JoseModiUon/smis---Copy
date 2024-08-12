<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
 * @property AdmittedStudent $admRefno
 * @property ProgCurriculum $progCurriculum
 * @property Student $student
 * @property StudentCategory $studentCategory
 * @property StudentStatus $status
 */
class StudentProgCurriculum extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'default', 'value' => null],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['registration_number'], 'string', 'max' => 20],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'student_id']],
            [['student_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentCategory::class, 'targetAttribute' => ['student_category_id' => 'std_category_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
     * Gets query for [[AdmittedStudent]].
     *
     * @return ActiveQuery
     */
    public function getAdmittedStudent(): ActiveQuery
    {
        return $this->hasOne(AdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return ActiveQuery
     */
    public function getProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(StudentStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * Gets query for [[Student]].
     *
     * @return ActiveQuery
     */
    public function getStudent(): ActiveQuery
    {
        return $this->hasOne(Student::class, ['student_id' => 'student_id']);
    }

    /**
     * Gets query for [[StudentCategory]].
     *
     * @return ActiveQuery
     */
    public function getStudentCategory(): ActiveQuery
    {
        return $this->hasOne(StudentCategory::class, ['std_category_id' => 'student_category_id']);
    }
}
