<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_exam_result".
 *
 * @property int $exam_result_id
 * @property string $fk_registration_number
 * @property string $result
 * @property string|null $ext_result
 * @property int $level_of_study
 * @property float|null $total_marks
 * @property int $courses_taken
 * @property int|null $gpa_courses
 * @property string|null $levelTrail
 * @property float $gpa
 */
class SmExamResult extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_exam_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_registration_number', 'result', 'level_of_study'], 'required'],
            [['level_of_study', 'courses_taken', 'gpa_courses'], 'default', 'value' => null],
            [['level_of_study', 'courses_taken', 'gpa_courses'], 'integer'],
            [['total_marks', 'gpa'], 'number'],
            [['levelTrail'], 'string'],
            [['fk_registration_number'], 'string', 'max' => 50],
            [['result'], 'string', 'max' => 15],
            [['ext_result'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'exam_result_id' => 'Exam Result ID',
            'fk_registration_number' => 'Fk Registration Number',
            'result' => 'Result',
            'ext_result' => 'Ext Result',
            'level_of_study' => 'Level Of Study',
            'total_marks' => 'Total Marks',
            'courses_taken' => 'Courses Taken',
            'gpa_courses' => 'Gpa Courses',
            'levelTrail' => 'Level Trail',
            'gpa' => 'Gpa',
        ];
    }

    public function getStudentProgCurriculum()
    {
        return $this->hasMany(SmStudentProgrammeCurriculum::class, ['registration_number' => 'fk_registration_number']);
    }
}
