<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_student_cohort_history".
 *
 * @property int $stud_cohort_hist_id
 * @property int $stud_id
 * @property int $cohort_id
 * @property string $entry_date
 * @property string|null $end_date
 * @property string $status
 * @property string|null $remark
 *
 * @property Cohort $cohort
 */
class StudentCohortHistory extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_cohort_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['stud_id', 'cohort_id'], 'required'],
            [['stud_id', 'cohort_id'], 'default', 'value' => null],
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'integer'],
            [['entry_date', 'end_date'], 'safe'],
            [['status'], 'string', 'max' => 15],
            [['remark'], 'string', 'max' => 100],
            [['stud_cohort_hist_id'], 'unique'],
            [['cohort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cohort::class, 'targetAttribute' => ['cohort_id' => 'cohort_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'stud_cohort_hist_id' => 'Stud Cohort Hist ID',
            'stud_id' => 'Stud ID',
            'cohort_id' => 'Cohort ID',
            'entry_date' => 'Entry Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'remark' => 'Remark',
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
}
