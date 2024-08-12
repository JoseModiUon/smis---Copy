<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "intakes".
 *
 * @property int $intake_code
 * @property string $intake_name
 *
 * @property AdmittedStudent[] $admittedStudents
 */
class Intakes extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_intakes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intake_code', 'intake_name'], 'required'],
            [['intake_code'], 'default', 'value' => null],
            [['intake_code'], 'integer'],
            [['intake_name'], 'string'],
            [['intake_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'intake_code' => 'Intake Code',
            'intake_name' => 'Intake Name',
        ];
    }

    /**
     * Gets query for [[AdmittedStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmittedStudents()
    {
        return $this->hasMany(AdmittedStudent::class, ['intake_code' => 'intake_code']);
    }
}
