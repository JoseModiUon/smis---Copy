<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_semester_group".
 *
 * @property int $student_semester_group_id
 * @property int $prog_curriculum_semester_id
 * @property int $student_semester_session_id
 * @property string $status
 *
 * @property OrgProgCurrSemester $progCurriculumSemester
 */
class SmStudentSemesterGroup extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_semester_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_semester_group_id', 'prog_curriculum_semester_id', 'student_semester_session_id'], 'required'],
            [['student_semester_group_id', 'prog_curriculum_semester_id', 'student_semester_session_id'], 'default', 'value' => null],
            [['student_semester_group_id', 'prog_curriculum_semester_id', 'student_semester_session_id'], 'integer'],
            [['status'], 'string', 'max' => 10],
            [['student_semester_group_id'], 'unique'],
            [['prog_curriculum_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrSemester::class, 'targetAttribute' => ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_semester_group_id' => 'Student Semester Group ID',
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'student_semester_session_id' => 'Student Semester Session ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[ProgCurriculumSemester]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculumSemester()
    {
        return $this->hasOne(OrgProgCurrSemester::class, ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }
}
