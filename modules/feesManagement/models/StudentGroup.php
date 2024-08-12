<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.sm_student_group".
 *
 * @property int $student_group_id
 * @property string|null $date
 * @property string|null $status
 * @property string|null $user_id
 * @property int|null $adm_refno
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class StudentGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'last_update'], 'safe'],
            [['status', 'user_id'], 'string'],
            [['adm_refno'], 'default', 'value' => null],
            [['adm_refno'], 'integer'],
            [['userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_group_id' => 'Student Group ID',
            'date' => 'Date',
            'status' => 'Status',
            'user_id' => 'User ID',
            'adm_refno' => 'Adm Refno',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    public function getAdmittedStudent(){
        return $this->hasOne(AdmittedStudent::class, ['adm_refno' => 'adm_refno']);        
    }
}
