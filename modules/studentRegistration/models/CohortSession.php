<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
 * @property Cohort $cohort
 * @property ProgCurrSemester $progCurriculumSemester
 */
class CohortSession extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_cohort_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['cohort_session_name', 'cohort_id', 'prog_curriculum_semester_id'], 'required'],
            [['cohort_id', 'prog_curriculum_semester_id'], 'default', 'value' => null],
            [['cohort_id', 'prog_curriculum_semester_id'], 'integer'],
            [['cohort_session_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
            [['cohort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cohort::class, 'targetAttribute' => ['cohort_id' => 'cohort_id']],
            [['prog_curriculum_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrSemester::class, 'targetAttribute' => ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
     * @return ActiveQuery
     */
    public function getCohort(): ActiveQuery
    {
        return $this->hasOne(Cohort::class, ['cohort_id' => 'cohort_id']);
    }

    /**
     * Gets query for [[ProgCurriculumSemester]].
     *
     * @return ActiveQuery
     */
    public function getProgCurrSemester(): ActiveQuery
    {
        return $this->hasOne(ProgCurrSemester::class, ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }
}
