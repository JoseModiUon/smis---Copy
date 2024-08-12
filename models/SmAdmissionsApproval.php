<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_admissions_approval".
 *
 * @property string|null $approval_comment
 * @property int $adm_approval_id
 * @property string $approver
 * @property string $approval_date
 * @property int $intake_id
 */
class SmAdmissionsApproval extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_admissions_approval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approval_comment', 'approver'], 'string'],
            [['adm_approval_id', 'approver', 'approval_date', 'intake_id'], 'required'],
            [['adm_approval_id', 'intake_id'], 'default', 'value' => null],
            [['adm_approval_id', 'intake_id'], 'integer'],
            [['approval_date'], 'safe'],
            [['adm_approval_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approval_comment' => 'Approval Comment',
            'adm_approval_id' => 'Adm Approval ID',
            'approver' => 'Approver',
            'approval_date' => 'Approval Date',
            'intake_id' => 'Intake ID',
        ];
    }
}
