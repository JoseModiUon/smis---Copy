<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:12 AM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;
/**
 * This is the model class for table "smis.ex_marksheet".
 *
 * @property int $marksheet_id
 * @property int $student_course_reg_id
 * @property float|null $course_work_mark
 * @property float|null $exam_mark
 * @property int|null $final_mark
 */
class Marksheet extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.ex_marksheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_course_reg_id'], 'required'],
            [['student_course_reg_id', 'final_mark'], 'default', 'value' => null],
            [['student_course_reg_id', 'final_mark'], 'integer'],
            [['course_work_mark', 'exam_mark'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'marksheet_id' => 'Marksheet ID',
            'student_course_reg_id' => 'Student Course Reg ID',
            'course_work_mark' => 'Course Work Mark',
            'exam_mark' => 'Exam Mark',
            'final_mark' => 'Final Mark',
        ];
    }
}
