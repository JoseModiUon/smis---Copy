<?php

namespace app\modules\studentRecords\models;

use app\modules\studentRegistration\models\SemesterCode;
use Yii;

/**
 * This is the model class for table "smis.org_academic_session_semester".
 *
 * @property int $acad_session_semester_id
 * @property int $acad_session_id
 * @property int $semester_code
 * @property string|null $acad_session_semester_desc
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class AcademicSessionSemester extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_academic_session_semester';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_id', 'semester_code'], 'required'],
            [['acad_session_id', 'semester_code'], 'default', 'value' => null],
            [['acad_session_id', 'semester_code'], 'integer'],
            [['last_update'], 'safe'],
            [['acad_session_semester_desc', 'ip_address'], 'string', 'max' => 50],
            [['userid'], 'string', 'max' => 20],
            [['user_action'], 'string', 'max' => 10],
            [['acad_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisOrgAcademicSession::class, 'targetAttribute' => ['acad_session_id' => 'acad_session_id']],
            [['semester_code'], 'exist', 'skipOnError' => true, 'targetClass' => SemesterCode::class, 'targetAttribute' => ['semester_code' => 'semester_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acad_session_semester_id' => 'Acad Session Semester ID',
            'acad_session_id' => 'Acad Session ID',
            'semester_code' => 'Semester Code',
            'acad_session_semester_desc' => 'Acad Session Semester Desc',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }

    public function getSemesterCode(){
        return $this->hasOne(SemesterCode::class, ['semester_code' => 'semester_code']);        
    }

}
