<?php

namespace app\models;

use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_id_details".
 *
 * @property int $stud_id_det_id
 * @property int $student_id_serial_no
 * @property string $student_id_status
 * @property string $remarks
 * @property string $status_date
 *
 * @property SmStudentId $studentIdSerialNo
 */
class SmStudentIdDetails extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_id_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stud_id_det_id', 'student_id_serial_no', 'student_id_status', 'remarks', 'status_date'], 'required'],
            [['stud_id_det_id', 'student_id_serial_no'], 'default', 'value' => null],
            [['stud_id_det_id', 'student_id_serial_no'], 'integer'],
            [['student_id_status', 'remarks'], 'string'],
            [['status_date'], 'safe'],
            [['stud_id_det_id'], 'unique'],
            [['student_id_serial_no'], 'exist',
                'skipOnError' => true,
                'targetClass' => SmStudentId::class,
                'targetAttribute' => [
                    'student_id_serial_no' => 'student_id_serial_no'
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
            'stud_id_det_id' => 'Stud Id Det ID',
            'student_id_serial_no' => 'Student Id Serial No',
            'student_id_status' => 'Student Id Status',
            'remarks' => 'Remarks',
            'status_date' => 'Status Date',
        ];
    }

    /**
     * Gets query for [[StudentIdSerialNo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentIdSerialNo()
    {
        return $this->hasOne(SmStudentId::class, ['student_id_serial_no' => 'student_id_serial_no']);
    }
}
