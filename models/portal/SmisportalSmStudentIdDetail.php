<?php

namespace app\models\portal;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_student_id_details".
 *
 * @property int $stud_id_det_id
 * @property int $student_id_serial_no
 * @property string $student_id_status
 * @property string $remarks
 * @property string $status_date
 */
class SmisportalSmStudentIdDetail extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id_details';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
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
            [['stud_id_det_id', 'student_id_serial_no', 'student_id_status', 'remarks', 'status_date'], 'required'],
            [['stud_id_det_id', 'student_id_serial_no'], 'default', 'value' => null],
            [['stud_id_det_id', 'student_id_serial_no'], 'integer'],
            [['student_id_status', 'remarks'], 'string'],
            [['status_date'], 'safe'],
            [['stud_id_det_id'], 'unique'],
            [['student_id_serial_no'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentId::class, 'targetAttribute' => ['student_id_serial_no' => 'student_id_serial_no']],
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
}
