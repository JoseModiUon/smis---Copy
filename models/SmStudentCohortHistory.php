<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_cohort_history".
 *
 * @property int $stud_cohort_hist_id
 * @property int $stud_id
 * @property int $cohort_id
 *
 * @property OrgCohort $cohort
 */
class SmStudentCohortHistory extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_student_cohort_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'required'],
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'default', 'value' => null],
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'integer'],
            [['stud_cohort_hist_id'], 'unique'],
            [['cohort_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgCohort::className(), 'targetAttribute' => ['cohort_id' => 'cohort_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'stud_cohort_hist_id' => 'Stud Cohort Hist ID',
            'stud_id' => 'Stud ID',
            'cohort_id' => 'Cohort ID',
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
}
