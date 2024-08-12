<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
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
class Cohort extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_cohort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    #[ArrayShape(['cohort_id' => "string", 'cohort_desc' => "string", 'cohort_year' => "string", 'adm_start_date' => "string", 'adm_end_date' => "string", 'cohort_status' => "string"])]
    public function attributeLabels(): array
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
}
