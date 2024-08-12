<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_prog_curr_unit".
 *
 * @property int $prog_curriculum_unit_id
 * @property int $org_unit_history_id
 * @property int $prog_curriculum_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string $status
 */
class ProgCurriculumUnit extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_prog_curr_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['org_unit_history_id', 'prog_curriculum_id', 'start_date'], 'required'],
            [['org_unit_history_id', 'prog_curriculum_id'], 'default', 'value' => null],
            [['org_unit_history_id', 'prog_curriculum_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['org_unit_history_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitHistory::class, 'targetAttribute' => ['org_unit_history_id' => 'org_unit_history_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_unit_id' => 'Prog Curriculum Unit ID',
            'org_unit_history_id' => 'Org Unit History ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUnitHistory(): ActiveQuery
    {
        return $this->hasOne(UnitHistory::class, ['org_unit_history_id' => 'org_unit_history_id']);
    }
}
