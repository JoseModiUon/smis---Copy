<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_level_requirement".
 *
 * @property int $prog_curr_level_req_id
 * @property int $prog_curriculum_id
 * @property int $prog_study_level
 * @property int $min_courses_taken
 * @property string $pass_type
 * @property int $min_pass_courses
 * @property string $gpa_choice
 * @property int $gpa_courses
 * @property int $gpa_weight
 * @property string $pass_result
 * @property string $pass_recommendation
 * @property string $fail_type
 * @property string $fail_result
 * @property string $fail_recommendation
 * @property string $incomplete_result
 * @property string $incomplete_recommendation
 *
 *
 * @property ProgCurrLevelRequirement[] $ProgCurrLevelRequirement
 */
//class OrgProgCurrOption extends \yii\db\ActiveRecord
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
            [['prog_curriculum_id', 'prog_study_level', 'pass_type'], 'required'],
            // [['prog_curr_level_req_id'], 'default', 'value' => null],
            // [['prog_curr_level_req_id'], 'integer'],
            [['prog_curriculum_id'], 'integer'],
            [['prog_study_level'], 'integer'],
            [['min_courses_taken'], 'integer'],
            [['pass_type'], 'string'],
            [['min_pass_courses'], 'integer'],
            [['gpa_choice'], 'integer'],
            [['gpa_weight'], 'integer'],
            [['pass_result'], 'string'],
            [['pass_recommendation'], 'string'],
            [['fail_type'], 'string'],
            [['fail_result'], 'string'],
            [['fail_recommendation'], 'string'],
            [['incomplete_result'], 'string'],
            [['incomplete_recommendation'], 'string'],
            [['prog_curr_level_req_id'], 'unique'],
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
            'gpa_weight' => 'Gpa Weight',
            'gpa_courses' => 'Gpa Courses',
            'pass_result' => 'Pass Result',
            'pass_recommendation' => 'Pass Recommendation',
            'fail_type' => 'Fail Type',
            'fail_result' => 'Fail Result',
            'fail_recommendation' => 'Fail Recommendation',
            'incomplete_result' => 'Incomplete Result',
            'incomplete_recommendation' => 'Incomplete Recommendation',
        ];
    }



    /**
     * Gets query for [[ProgCurrLevelRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrLevelRequirement()
    {
        return $this->hasMany(ProgCurrLevelRequirement::className(), ['prog_curr_level_req_id' => 'prog_curr_level_req_id']);
    }
    /**
     * Gets query for [[ProgCurrLevelRequirement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCurriculum()
    {
        return $this->hasOne(OrgProgrammeCurriculum::className(), ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    public function getRelatedModels()
    {
        return $this->hasMany(OrgProgrammes::class, ['prog_id' => 'prog_curriculum_id']);
    }
}
