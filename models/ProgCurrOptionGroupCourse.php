<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "prog_curr_option_group_course".
 *
 * @property int $prog_curr_option_group_course_id
 * @property int $fk_option_group_req_id
 * @property int $fk_prog_curr_option_course_id
 * @property int $credit_factor
 *
 * @property ProgCurrOptionGroupRequirement $fkOptionGroupReq
 * @property OrgProgCurrOptionCourse $fkProgCurrOptionCourse
 */
class ProgCurrOptionGroupCourse extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prog_curr_option_group_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_option_group_req_id', 'fk_prog_curr_option_course_id'], 'required'],
            [['fk_option_group_req_id', 'fk_prog_curr_option_course_id', 'credit_factor'], 'default', 'value' => null],
            [['fk_option_group_req_id', 'fk_prog_curr_option_course_id', 'credit_factor'], 'integer'],
            [['fk_prog_curr_option_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrOptionCourse::class, 'targetAttribute' => ['fk_prog_curr_option_course_id' => 'option_course_id']],
            [['fk_option_group_req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrOptionGroupRequirement::class, 'targetAttribute' => ['fk_option_group_req_id' => 'option_group_requirement_ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curr_option_group_course_id' => 'Prog Curr Option Group Course ID',
            'fk_option_group_req_id' => 'Fk Option Group Req ID',
            'fk_prog_curr_option_course_id' => 'Fk Prog Curr Option Course ID',
            'credit_factor' => 'Credit Factor',
        ];
    }

    /**
     * Gets query for [[FkOptionGroupReq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkOptionGroupReq()
    {
        return $this->hasOne(ProgCurrOptionGroupRequirement::class, ['option_group_requirement_ID' => 'fk_option_group_req_id']);
    }

    /**
     * Gets query for [[FkProgCurrOptionCourse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkProgCurrOptionCourse()
    {
        return $this->hasOne(OrgProgCurrOptionCourse::class, ['option_course_id' => 'fk_prog_curr_option_course_id']);
    }
}
