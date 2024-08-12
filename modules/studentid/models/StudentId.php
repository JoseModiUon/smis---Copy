<?php

namespace app\modules\studentid\models;

/**
 *
 * @property string $id_remarks
 */
class StudentId extends \app\models\SmStudentId
{

    const LOST = 'LOST';
    const ACTIVE = 'ACTIVE';
    const FOUND = 'FOUND';
    const EXPIRED = 'EXPIRED';

    public $id_remarks;


    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['id_remarks'], 'required'];

        $rules[] = [['student_prog_curr_id'], 'exist',
            'skipOnError' => true,
            'targetClass' => StudentProgrammeCurriculum::class,
            'targetAttribute' => [
                'student_prog_curr_id' => 'student_prog_curriculum_id'
            ]];
        return $rules;
    }

    public function idStudentStatus(): array
    {
        return [
            self::FOUND => self::ACTIVE,
            self::LOST => self::LOST,
        ];
    }

    public function idStatus(): array
    {
        return [
            self::FOUND => self::ACTIVE,
            self::LOST => self::LOST,
            self::ACTIVE => self::ACTIVE,
            self::EXPIRED => self::EXPIRED
        ];
    }

    /**
     * @return array|string[]
     */
    public
    function attributeLabels(): array
    {
        $labels = parent::attributeLabels();
        $labels['student_id_serial_no'] = 'Serial number';
        $labels['student_prog_curr_id'] = 'Programme';
        $labels['id_remarks'] = 'Remarks';
        $labels['id_status'] = 'Status';
        return $labels;
    }
}
