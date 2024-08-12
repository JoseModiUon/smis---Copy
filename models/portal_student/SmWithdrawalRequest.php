<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_withdrawal_request".
 *
 * @properwslty int $withdrawal_request_id
 * @property int $withdrawal_type_id
 * @property string $request_date
 * @property string $reason
 * @property string $approval_status
 * @property int $student_id
 */
class SmWithdrawalRequest extends \yii\db\ActiveRecord
{
    use UserTracking;


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_withdrawal_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_request_id', 'withdrawal_type_id', 'request_date', 'reason', 'approval_status', 'student_id'], 'required'],
            [['withdrawal_request_id', 'withdrawal_type_id', 'student_id'], 'default', 'value' => null],
            [['withdrawal_request_id', 'withdrawal_type_id', 'student_id'], 'integer'],
            [['request_date'], 'safe'],
            [['approval_status'], 'string'],
            [['reason'], 'string', 'max' => 250],
            [['withdrawal_request_id'], 'unique'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'student_id']],
            [['withdrawal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmWithdrawalType::class, 'targetAttribute' => ['withdrawal_type_id' => 'withdrawal_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'withdrawal_request_id' => 'Withdrawal Request ID',
            'withdrawal_type_id' => 'Withdrawal Type ID',
            'request_date' => 'Request Date',
            'reason' => 'Reason',
            'approval_status' => 'Approval Status',
            'student_id' => 'Student ID',
        ];
    }
    public function getSmWithdrawalType()
    {
        return $this->hasOne(SmWithdrawalType::className(), ['withdrawal_type_id' => 'withdrawal_type_id']);
    }
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['student_id' => 'student_id']);
    }
}
