<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_cohort".
 *
 * @property int $cohort_id
 * @property string $cohort_desc
 * @property string|null $cohort_year
 * @property string|null $adm_start_date
 * @property string|null $adm_end_date
 * @property string|null $cohort_status
 */
class OrgCohort extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_cohort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cohort_desc'], 'required'],
            [['adm_start_date', 'adm_end_date'], 'safe'],
            [['cohort_desc'], 'string', 'max' => 50],
            [['cohort_year'], 'string', 'max' => 9],
            [['cohort_status'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cohort_id' => 'Cohort ID',
            'cohort_desc' => 'Cohort Desc',
            'cohort_year' => 'Cohort Year',
            'adm_start_date' => 'Adm Start Date',
            'adm_end_date' => 'Adm End Date',
            'cohort_status' => 'Cohort Status',
        ];
    }

    /**
     * Gets query for [[OrgCohortSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCohortSessions()
    {
        return $this->hasMany(OrgCohortSession::className(), ['cohort_id' => 'cohort_id']);
    }

    /**
     * Gets query for [[SmStudentCohortHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentCohortHistories()
    {
        return $this->hasMany(SmStudentCohortHistory::className(), ['cohort_id' => 'cohort_id']);
    }
}
