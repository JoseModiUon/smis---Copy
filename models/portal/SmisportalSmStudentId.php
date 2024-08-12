<?php

namespace app\models\portal;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_student_id".
 *
 * @property int $student_id_serial_no
 * @property int $student_prog_curr_id
 * @property string $issuance_date
 * @property string $valid_from
 * @property string $valid_to
 * @property int $barcode
 * @property string|null $id_status
 * @property string|null $printing_date
 */
class SmisportalSmStudentId extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id';
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
            [['student_id_serial_no', 'student_prog_curr_id', 'issuance_date', 'valid_from', 'valid_to', 'barcode'], 'required'],
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode'], 'default', 'value' => null],
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode'], 'integer'],
            [['issuance_date', 'valid_from', 'valid_to', 'printing_date'], 'safe'],
            [['id_status'], 'string', 'max' => 15],
            [['student_id_serial_no'], 'unique'],
            [['student_prog_curr_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalSmStudentProgrammeCurriculum::class, 'targetAttribute' => ['student_prog_curr_id' => 'student_prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id_serial_no' => 'Student Id Serial No',
            'student_prog_curr_id' => 'Student Prog Curr ID',
            'issuance_date' => 'Issuance Date',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
            'barcode' => 'Barcode',
            'id_status' => 'Id Status',
            'printing_date' => 'Printing Date',
        ];
    }
}
