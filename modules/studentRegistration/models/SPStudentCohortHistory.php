<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
use app\models\traits\UserTracking;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "smisportal.sm_student_cohort_history".
 *
 * @property int $stud_cohort_hist_id
 * @property int $stud_id
 * @property int $cohort_id
 */
class SPStudentCohortHistory extends ActiveRecord
{
//    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_cohort_history';
    }

    /**
     * @return Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'required'],
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'default', 'value' => null],
            [['stud_cohort_hist_id', 'stud_id', 'cohort_id'], 'integer'],
            [['stud_cohort_hist_id'], 'unique'],
//            [['cohort_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgCohort::class, 'targetAttribute' => ['cohort_id' => 'cohort_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['stud_cohort_hist_id' => "string", 'stud_id' => "string", 'cohort_id' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'stud_cohort_hist_id' => 'Stud Cohort Hist ID',
            'stud_id' => 'Stud ID',
            'cohort_id' => 'Cohort ID',
        ];
    }
}
