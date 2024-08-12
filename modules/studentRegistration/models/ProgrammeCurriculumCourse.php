<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 11:07 AM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

class ProgrammeCurriculumCourse extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_prog_curr_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_hours', 'level_of_study'], 'required'],
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester', 'prerequisite'], 'default', 'value' => null],
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester', 'prerequisite'], 'integer'],
            [['credit_hours'], 'number'],
            [['has_course_work'], 'boolean'],
            [['status'], 'string', 'max' => 10],
            [['prog_curriculum_course_id'], 'unique'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitCourse::class, 'targetAttribute' => ['course_id' => 'course_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'course_id' => 'Course ID',
            'credit_factor' => 'Credit Factor',
            'credit_hours' => 'Credit Hours',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
            'prerequisite' => 'Prerequisite',
            'status' => 'Status',
            'has_course_work' => 'Has Course Work',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse(): ActiveQuery
    {
        return $this->hasOne(Course::class, ['course_id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }
}
