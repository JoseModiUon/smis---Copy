<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_academic_session_semester".
 *
 * @property int $acad_session_semester_id
 * @property int $acad_session_id
 * @property int $semester_code
 * @property string|null $acad_session_semester_desc
 *
 * @property OrgAcademicSession $acadSession
 * @property OrgProgCurrSemester[] $orgProgCurrSemesters
 * @property OrgSemesterCode $semesterCode
 */
class OrgAcademicSessionSemester extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_academic_session_semester';
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
            [['acad_session_id', 'semester_code'], 'required'],
            [['acad_session_id', 'semester_code'], 'default', 'value' => null],
            [['acad_session_id', 'semester_code'], 'integer'],
            [['acad_session_semester_desc'], 'string', 'max' => 50],
            [['acad_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgAcademicSession::className(), 'targetAttribute' => ['acad_session_id' => 'acad_session_id']],
            [['semester_code'], 'exist', 'skipOnError' => true, 'targetClass' => OrgSemesterCode::className(), 'targetAttribute' => ['semester_code' => 'semester_code']],
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
        ];
    }

    /**
     * Gets query for [[AcadSession]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcadSession()
    {
        return $this->hasOne(OrgAcademicSession::className(), ['acad_session_id' => 'acad_session_id']);
    }

    /**
     * Gets query for [[OrgProgCurrSemesters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrSemesters()
    {
        return $this->hasMany(OrgProgCurrSemester::className(), ['acad_session_semester_id' => 'acad_session_semester_id']);
    }

    /**
     * Gets query for [[SemesterCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSemesterCode()
    {
        return $this->hasOne(OrgSemesterCode::className(), ['semester_code' => 'semester_code']);
    }
}
