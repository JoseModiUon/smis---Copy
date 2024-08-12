<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_prog_curr_semester".
 *
 * @property int $prog_curriculum_semester_id
 * @property int $prog_curriculum_id
 * @property int $acad_session_semester_id
 *
 * @property OrgAcademicSessionSemester $acadSessionSemester
 * @property OrgCohortSession[] $orgCohortSessions
 * @property OrgProgCurrSemesterGroup[] $orgProgCurrSemesterGroups
 * @property OrgProgrammeCurriculum $progCurriculum
 * @property SmStudentSemesterGroup[] $smStudentSemesterGroups
 */
class OrgProgCurrSemester extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_prog_curr_semester';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prog_curriculum_id', 'acad_session_semester_id'], 'required'],
            [['prog_curriculum_id', 'acad_session_semester_id'], 'default', 'value' => null],
            [['prog_curriculum_id', 'acad_session_semester_id'], 'integer'],
            [['acad_session_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgAcademicSessionSemester::className(), 'targetAttribute' => ['acad_session_semester_id' => 'acad_session_semester_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgrammeCurriculum::className(), 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'acad_session_semester_id' => 'Acad Session Semester ID',
        ];
    }

    /**
     * Gets query for [[AcadSessionSemester]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcadSessionSemester()
    {
        return $this->hasOne(OrgAcademicSessionSemester::className(), ['acad_session_semester_id' => 'acad_session_semester_id']);
    }

    /**
     * Gets query for [[OrgCohortSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCohortSessions()
    {
        return $this->hasMany(OrgCohortSession::className(), ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }

    /**
     * Gets query for [[OrgProgCurrSemesterGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgProgCurrSemesterGroups()
    {
        return $this->hasMany(OrgProgCurrSemesterGroup::className(), ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculum()
    {
        return $this->hasOne(OrgProgrammeCurriculum::className(), ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * Gets query for [[SmStudentSemesterGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentSemesterGroups()
    {
        return $this->hasMany(SmStudentSemesterGroup::className(), ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }
    
    /**
     * Gets query for [[OrgSemesterType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgSemesterType()
    {
        return $this->hasOne(OrgSemesterType::className(), ['semester_type_id' => 'semester_type_id']);
    }
}
