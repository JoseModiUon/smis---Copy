<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.sm_withdrawal_approval".
 *
 * @property int $withdrawal_approval_id
 * @property int $withdrawal_request_id
 * @property int $approver_id
 * @property string|null $comments
 * @property string $approval_status
 */
class SmWithdrawalApproval extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_withdrawal_approval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withdrawal_request_id', 'approver_id', 'approval_status'], 'required'],
            [['withdrawal_request_id', 'approver_id'], 'default', 'value' => null],
            [['withdrawal_request_id', 'approver_id'], 'integer'],
            [['comments'], 'string'],
            [['approval_status'], 'string', 'max' => 50],
            [['approver_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmApprover::class, 'targetAttribute' => ['approver_id' => 'approver_id']],
            [['withdrawal_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmWithdrawalRequest::class, 'targetAttribute' => ['withdrawal_request_id' => 'withdrawal_request_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'withdrawal_approval_id' => 'Withdrawal Approval ID',
            'withdrawal_request_id' => 'Withdrawal Request ID',
            'approver_id' => 'Approver ID',
            'comments' => 'Comments',
            'approval_status' => 'Approval Status',
        ];
    }

    public function getWithdrawalRequest()
    {
        return $this->hasOne(SmWithdrawalRequest::class, ['withdrawal_request_id' => 'withdrawal_request_id']);
    }

    public function getApprovals()
    {
        return $this->hasOne(SmApprover::class, ['approver_id' => 'approver_id']);
    }
}
