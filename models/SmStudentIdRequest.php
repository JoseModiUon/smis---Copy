<?php

namespace app\models;

use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_id_request".
 *
 * @property int $request_id
 * @property int $request_type_id
 * @property int $student_prog_curr_id
 * @property string $request_date
 * @property int $status_id
 * @property int|null $receipt_number
 * @property string $source
 * @property bool|null $student_id_sync_status
 *
 * @property SmIdRequestType $requestType
 * @property SmIdRequestStatus $status
 * @property SmStudentProgrammeCurriculum $studentProgCurr
 */
class SmStudentIdRequest extends \app\extended\BaseModel
{
    use UserTracking;

    public int $receipt_number = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_id_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_type_id', 'student_prog_curr_id', 'request_date', 'status_id', 'source'], 'required'],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'default', 'value' => null],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'integer'],
            [['student_id_sync_status'], 'boolean'],
            [['request_date'], 'safe'],
            [['source'], 'string', 'max' => 30],
            [['status_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => SmIdRequestStatus::class,
                'targetAttribute' => [
                    'status_id' => 'status_id'
                ]
            ],
            [['request_type_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => SmIdRequestType::class,
                'targetAttribute' => [
                    'request_type_id' => 'request_type_id'
                ]
            ],
            [['student_prog_curr_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => SmStudentProgrammeCurriculum::class,
                'targetAttribute' => [
                    'student_prog_curr_id' => 'student_prog_curriculum_id'
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'request_type_id' => 'Request Type ID',
            'student_prog_curr_id' => 'Student Prog Curr ID',
            'request_date' => 'Request Date',
            'status_id' => 'Status ID',
            'receipt_number' => 'Receipt Number',
            'source' => 'Source',
            'student_id_sync_status' => 'ID Sync Status',
        ];
    }

    /**
     * Gets query for [[RequestType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestType()
    {
        return $this->hasOne(SmIdRequestType::class, ['request_type_id' => 'request_type_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(SmIdRequestStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * Gets query for [[StudentProgCurr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentProgCurr()
    {
        return $this->hasOne(SmStudentProgrammeCurriculum::class, ['student_prog_curriculum_id' => 'student_prog_curr_id']);
    }
}
