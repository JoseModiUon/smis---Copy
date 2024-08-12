<?php

namespace app\models\portal;

use Yii;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "smisportal.sm_student_id_request".
 *
 * @property int $request_id
 * @property int $request_type_id
 * @property int $student_prog_curr_id
 * @property string $request_date
 * @property int $status_id
 * @property int|null $receipt_number
 * @property string $source
 * @property bool|null $student_id_sync_status
 */
class SmisportalSmStudentIdRequest extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id_request';
    }

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
    public function rules()
    {
        return [
            [['request_type_id', 'student_prog_curr_id', 'request_date', 'status_id', 'source'], 'required'],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'default', 'value' => null],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'integer'],
            [['request_date'], 'safe'],
            [['student_id_sync_status'], 'boolean'],
            [['source'], 'string', 'max' => 30],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmIdRequestStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
            [['request_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmIdRequestType::class, 'targetAttribute' => ['request_type_id' => 'request_type_id']],
            [['student_prog_curr_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentProgrammeCurriculum::class, 'targetAttribute' => ['student_prog_curr_id' => 'student_prog_curriculum_id']],
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
            'student_id_sync_status' => 'Student Id Sync Status',
        ];
    }
}
