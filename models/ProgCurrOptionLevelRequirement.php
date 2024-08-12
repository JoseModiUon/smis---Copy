<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_option_level_requirement".
 *
 * @property int $prog_curr_option_level_req_id
 * @property int $fk_option_id Programme curriculum option ID
 * @property int $level_of_study
 * @property int $min_courses
 * @property int $max_courses
 * @property float $gpa_weight
 * @property int $no_of_depts
 * @property int $min_pass
 * @property int $gpa_courses
 * @property string|null $pass_type BEST, ALL
 * @property string|null $gpa_choice BEST,ALL
 *
 * @property OrgProgCurrOption $fkOption
 * @property ProgCurrOptionGroupRequirement[] $progCurrOptionGroupRequirements
 */
class ProgCurrOptionLevelRequirement extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prog_curr_option_level_requirement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_option_id', 'level_of_study'], 'required'],
            [['fk_option_id', 'level_of_study', 'min_courses', 'max_courses', 'no_of_depts', 'min_pass', 'gpa_courses'], 'default', 'value' => null],
            [['fk_option_id', 'level_of_study', 'min_courses', 'max_courses', 'no_of_depts', 'min_pass', 'gpa_courses'], 'integer'],
            [['gpa_weight'], 'number'],
            [['pass_type', 'gpa_choice'], 'string', 'max' => 5],
            [['fk_option_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrOption::class, 'targetAttribute' => ['fk_option_id' => 'option_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_option_level_req_id' => 'Prog Curr Option Level Req ID',
            'fk_option_id' => 'Fk Option ID',
            'level_of_study' => 'Level Of Study',
            'min_courses' => 'Min Courses',
            'max_courses' => 'Max Courses',
            'gpa_weight' => 'Gpa Weight',
            'no_of_depts' => 'No Of Depts',
            'min_pass' => 'Min Pass',
            'gpa_courses' => 'Gpa Courses',
            'pass_type' => 'Pass Type',
            'gpa_choice' => 'Gpa Choice',
        ];
    }

    /**
     * Gets query for [[FkOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkOption()
    {
        return $this->hasOne(OrgProgCurrOption::class, ['option_id' => 'fk_option_id']);
    }

    /**
     * Gets query for [[ProgCurrOptionGroupRequirements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurrOptionGroupRequirements()
    {
        return $this->hasMany(ProgCurrOptionGroupRequirement::class, ['fk_option_level_req_id' => 'prog_curr_option_level_req_id']);
    }
}
