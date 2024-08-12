<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_readmission".
 *
 * @property int $readmission_id
 * @property int $stud_prog_curr_id
 * @property string $entry_date
 * @property string|null $reason
 * @property string|null $registration_no
 * @property string|null $approval_status
 * @property string|null $entry_remarks
 * @property string|null $approval_remarks
 * @property string|null $entered_by
 * @property string|null $approved_by
 * @property string|null $approval_date
 */
class SmReadmission extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_readmission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['readmission_id', 'stud_prog_curr_id', 'entry_date'], 'required'],
            [['readmission_id', 'stud_prog_curr_id'], 'default', 'value' => null],
            [['readmission_id', 'stud_prog_curr_id'], 'integer'],
            [['entry_date', 'approval_date'], 'safe'],
            [['reason', 'entry_remarks', 'approval_remarks'], 'string', 'max' => 250],
            [['registration_no'], 'string', 'max' => 50],
            [['approval_status'], 'string', 'max' => 30],
            [['entered_by', 'approved_by'], 'string', 'max' => 15],
            [['readmission_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'readmission_id' => 'Readmission ID',
            'stud_prog_curr_id' => 'Stud Prog Curr ID',
            'entry_date' => 'Entry Date',
            'reason' => 'Reason',
            'registration_no' => 'Registration No',
            'approval_status' => 'Approval Status',
            'entry_remarks' => 'Entry Remarks',
            'approval_remarks' => 'Approval Remarks',
            'entered_by' => 'Entered By',
            'approved_by' => 'Approved By',
            'approval_date' => 'Approval Date',
        ];
    }
}
