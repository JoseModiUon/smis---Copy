<?php

namespace app\models\portal;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_student_id_status".
 *
 * @property int $id_status_no
 * @property string $status_name
 * @property int $student_id_serial_no
 */
class SmisportalSmStudentIdStatus extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id_status';
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
            [['id_status_no', 'status_name', 'student_id_serial_no'], 'required'],
            [['id_status_no', 'student_id_serial_no'], 'default', 'value' => null],
            [['id_status_no', 'student_id_serial_no'], 'integer'],
            [['status_name'], 'string', 'max' => 20],
            [['id_status_no'], 'unique'],
            [['student_id_serial_no'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentId::class, 'targetAttribute' => ['student_id_serial_no' => 'student_id_serial_no']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_status_no' => 'Id Status No',
            'status_name' => 'Status Name',
            'student_id_serial_no' => 'Student Id Serial No',
        ];
    }
}
