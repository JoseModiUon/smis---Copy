<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_academic_session".
 *
 * @property int $acad_session_id
 * @property string $acad_session_name
 * @property string|null $acad_session_desc
 * @property string $start_date
 * @property string $end_date
 *
 * @property OrgAcademicSessionSemester[] $orgAcademicSessionSemesters
 * @property SmAcademicProgress[] $smAcademicProgresses
 */
class OrgAcademicSession extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_academic_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_name', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['acad_session_name'], 'string', 'max' => 50],
            [['acad_session_desc'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acad_session_id' => 'Acad Session ID',
            'acad_session_name' => 'Name',
            'acad_session_desc' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * Gets query for [[OrgAcademicSessionSemesters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgAcademicSessionSemesters()
    {
        return $this->hasMany(OrgAcademicSessionSemester::className(), ['acad_session_id' => 'acad_session_id']);
    }

    /**
     * Gets query for [[SmAcademicProgresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmAcademicProgresses()
    {
        return $this->hasMany(SmAcademicProgress::className(), ['acad_session_id' => 'acad_session_id']);
    }
}
