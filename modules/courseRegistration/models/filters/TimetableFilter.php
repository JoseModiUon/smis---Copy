<?php

namespace app\modules\courseRegistration\models\filters;

use yii\base\Model;

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 6/18/2024
 * @time: 2:20 PM
 */

/**
 * @property string $prog_curriculum_id
 * @property string $acad_session_id
 * @property string $study_centre_id
 * @property string $study_group_id
 * @property string $semester_type_id
 * @property string $semester_code
 * @property string $programme_level_id
 */
class TimetableFilter extends Model
{
    public ?string $prog_curriculum_id = null;
    public ?string $acad_session_id = null;
    public ?string $study_centre_id = null;
    public ?string $study_group_id = null;
    public ?string $semester_type_id = null;
    public ?string $semester_code = null;
    public ?string $programme_level_id = null;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [[
                'prog_curriculum_id',
                'acad_session_id',
                'study_centre_id',
                'study_group_id',
                'semester_type_id',
                'semester_code',
                'programme_level_id'
            ], 'string'],

            [[
                'prog_curriculum_id',
                'acad_session_id',
                'study_centre_id',
                'study_group_id',
                'semester_type_id',
                'semester_code',
                'programme_level_id'
            ], 'required'],

            [[
                'prog_curriculum_id',
                'acad_session_id',
                'study_centre_id',
                'study_group_id',
                'semester_type_id',
                'semester_code',
                'programme_level_id'
            ], 'trim'],
            [[
                'prog_curriculum_id',
                'acad_session_id',
                'study_centre_id',
                'study_group_id',
                'semester_type_id',
                'semester_code',
                'programme_level_id'
            ], 'default']
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_id' => 'Program curriculum',
            'acad_session_id' => 'Academic year',
            'study_centre_id' => 'Study center',
            'study_group_id' => 'Study group',
            'semester_type_id' => 'Semester type',
            'semester_code' => 'Semester',
            'programme_level_id' => 'Level of study',
        ];
    }
}