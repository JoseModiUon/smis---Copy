<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_sponsor".
 *
 * @property int $sponsor_id
 * @property string $sponsor_code
 * @property string $sponsor_name
 * @property string $status
 */
class SmStudentSponsor extends \yii\db\ActiveRecord
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
        return 'smisportal.sm_student_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_code', 'sponsor_name'], 'required'],
            [['sponsor_code', 'status'], 'string', 'max' => 10],
            [['sponsor_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_id' => 'Sponsor ID',
            'sponsor_code' => 'Sponsor Code',
            'sponsor_name' => 'Sponsor Name',
            'status' => 'Status',
        ];
    }
}
