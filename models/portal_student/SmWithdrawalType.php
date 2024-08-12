<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_withdrawal_type".
 *
 * @property int $withdrawal_type_id
 * @property string $withdrawal_type_name
 */
class SmWithdrawalType extends \yii\db\ActiveRecord
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
        return 'smisportal.sm_withdrawal_type';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_type_name'], 'required'],
            [['withdrawal_type_name'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'withdrawal_type_id' => 'Withdrawal Type ID',
            'withdrawal_type_name' => 'Withdrawal Type Name',
        ];
    }
	
}
