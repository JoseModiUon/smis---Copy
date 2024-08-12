<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "ex_marksheet".
 *
 * @property int $marksheet_id
 * @property int $student_course_reg_id
 * @property float|null $course_work_mark
 * @property float|null $exam_mark
 * @property int|null $final_mark
 *
 * @property CrCourseRegistration $studentCourseReg
 */
class ExMarksheet extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.ex_marksheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marksheet_id', 'student_course_reg_id'], 'required'],
            [['marksheet_id', 'student_course_reg_id', 'final_mark'], 'default', 'value' => null],
            [['marksheet_id', 'student_course_reg_id', 'final_mark'], 'integer'],
            [['course_work_mark', 'exam_mark'], 'number'],
            [['marksheet_id'], 'unique'],
            [['student_course_reg_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrCourseRegistration::class, 'targetAttribute' => ['student_course_reg_id' => 'student_course_reg_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'marksheet_id' => 'Marksheet ID',
            'student_course_reg_id' => 'Student Course Reg ID',
            'course_work_mark' => 'Course Work Mark',
            'exam_mark' => 'Exam Mark',
            'final_mark' => 'Final Mark',
        ];
    }

    /**
     * Gets query for [[StudentCourseReg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentCourseReg()
    {
        return $this->hasOne(CrCourseRegistration::class, ['student_course_reg_id' => 'student_course_reg_id']);
    }
}
