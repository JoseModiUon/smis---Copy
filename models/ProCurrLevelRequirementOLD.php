<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.prog_curr_level_requirement".
 *
 * @property int $prog_curr_level_req_id
 * @property int $prog_curriculum_id
 * @property int $prog_study_level
 * @property int $min_courses_taken
 * @property string|null $pass_type ALL, BEST
 * @property int $min_pass_courses
 * @property string|null $gpa_choice ALL,BEST
 * @property int $gpa_courses
 * @property int $gpa_weight
 * @property string $pass_result
 * @property string $pass_recommendation
 * @property string|null $fail_type
 * @property string $fail_result
 * @property string $fail_recommendation
 * @property string $incomplete_result
 * @property string $incomplete_recommendation
 */
class ProgCurrLevelRequirement extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.prog_curr_level_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'prog_study_level', 'min_courses_taken', 'min_pass_courses', 'pass_result', 'pass_recommendation', 'fail_result', 'fail_recommendation', 'incomplete_result', 'incomplete_recommendation'], 'required'],
            [['prog_curriculum_id', 'prog_study_level', 'min_courses_taken', 'min_pass_courses', 'gpa_courses', 'gpa_weight'], 'default', 'value' => null],
            [['prog_curriculum_id', 'prog_study_level', 'min_courses_taken', 'min_pass_courses', 'gpa_courses', 'gpa_weight'], 'integer'],
            [['pass_type', 'gpa_choice'], 'string', 'max' => 4],
            [['pass_result'], 'string', 'max' => 15],
            [['pass_recommendation'], 'string', 'max' => 50],
            [['fail_type'], 'string', 'max' => 20],
            [['fail_result', 'incomplete_result'], 'string', 'max' => 30],
            [['fail_recommendation', 'incomplete_recommendation'], 'string', 'max' => 150],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisOrgProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_level_req_id' => 'Prog Curr Level Req ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'prog_study_level' => 'Prog Study Level',
            'min_courses_taken' => 'Min Courses Taken',
            'pass_type' => 'Pass Type',
            'min_pass_courses' => 'Min Pass Courses',
            'gpa_choice' => 'Gpa Choice',
            'gpa_courses' => 'Gpa Courses',
            'gpa_weight' => 'Gpa Weight',
            'pass_result' => 'Pass Result',
            'pass_recommendation' => 'Pass Recommendation',
            'fail_type' => 'Fail Type',
            'fail_result' => 'Fail Result',
            'fail_recommendation' => 'Fail Recommendation',
            'incomplete_result' => 'Incomplete Result',
            'incomplete_recommendation' => 'Incomplete Recommendation',
        ];
    }

    public function getProgrammeCurriculum()
    {
        return $this->hasOne(OrgProgrammeCurriculum::className(), ['prog_curriculum_id' => 'prog_curriculum_id']);
    }
}
