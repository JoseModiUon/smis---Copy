<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_cohort_session".
 *
 * @property int $cohort_session_id
 * @property string $cohort_session_name
 * @property int $cohort_id
 * @property int $prog_curriculum_semester_id
 * @property string $status
 *
 * @property OrgCohort $cohort
 * @property OrgProgCurrSemester $progCurriculumSemester
 */
class OrgCohortSession extends \yii\db\ActiveRecord
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_cohort_session';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cohort_session_name', 'cohort_id', 'prog_curriculum_semester_id'], 'required'],
            [['cohort_id', 'prog_curriculum_semester_id'], 'default', 'value' => null],
            [['cohort_id'], 'integer'],
            [['cohort_session_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
            [['cohort_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgCohort::className(), 'targetAttribute' => ['cohort_id' => 'cohort_id']],
            [['prog_curriculum_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgProgCurrSemester::className(), 'targetAttribute' => ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cohort_session_id' => 'Cohort Session ID',
            'cohort_session_name' => 'Cohort Session Name',
            'cohort_id' => 'Cohort ID',
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Cohort]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCohort()
    {
        return $this->hasOne(OrgCohort::className(), ['cohort_id' => 'cohort_id']);
    }

    /**
     * Gets query for [[ProgCurriculumSemester]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgCurriculumSemester()
    {
        return $this->hasOne(OrgProgCurrSemester::className(), ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }
}
