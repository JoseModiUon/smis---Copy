<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_intakes".
 *
 * @property int $intake_code
 * @property string $intake_name
 *
 * @property SmAdmittedStudent[] $smAdmittedStudents
 */
class SmIntakes extends \app\extended\BaseModel
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_intakes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['intake_name'], 'required'],
            [['intake_name'], 'string'],
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
     * Gets query for [[SmAdmittedStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAdmittedStudents()
    {
        return $this->hasMany(SmAdmittedStudent::class, ['intake_code' => 'intake_code']);
    }
}
