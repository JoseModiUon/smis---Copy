<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_curr_option".
 *
 * @property int $option_id
 * @property int $prog_cur_id
 * @property string $option_code
 * @property string $option_name
 * @property string $degree_id
 * @property string|null $option_desc
 * @property string $option_status
 * @property string|null $progress_type
 *
 * @property OrgProgCurrOptionCourses[] $orgProgCurrOptionCourses
 */
class OrgProgCurrOption extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_prog_curr_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_code', 'option_name', 'option_status','prog_cur_id'], 'required'],
           // [['option_id'], 'default', 'value' => null],
            [['option_id'], 'safe'],
            [['option_code'], 'string', 'max' => 10],
            [['option_name'], 'string', 'max' => 25],
            [['option_desc'], 'string', 'max' => 150],
            [['option_status', 'progress_type'], 'string', 'max' => 12],
           // [['option_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'option_code' => 'Option Code',
            'option_name' => 'Option Name',
            'option_desc' => 'Option Desc',
            'option_status' => 'Option Status',
            'progress_type' => 'Progress Type',
            'prog_cur_id' => 'Programme Curriculum ID',
        ];
    }

    /**
     * Gets query for [[OrgProgCurrOptionCourses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrOptionCourses()
    {
        return $this->hasMany(OrgProgCurrOptionCourses::className(), ['option_id' => 'option_id']);
    }
    /**
     * Gets query for [[OrgProgCurrOptionCourses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCurriculum()
    {
        return $this->hasMany(OrgProgrammeCurriculum::className(), ['prog_curriculum_id' => 'prog_cur_id']);
    }
}
