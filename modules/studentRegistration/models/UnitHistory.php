<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_unit_history".
 *
 * @property int $org_unit_history_id
 * @property int $org_unit_id
 * @property int $org_type_id
 * @property int|null $parent_id
 * @property int|null $successor_id
 * @property int|null $unit_head_id
 * @property int|null $unit_head_user_id
 * @property string $start_date
 * @property string|null $end_date
 * @property int|null $user_id
 * @property string $date_created
 */
class UnitHistory extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_unit_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['org_unit_id', 'org_type_id', 'start_date'], 'required'],
            [['org_unit_id', 'org_type_id', 'parent_id', 'successor_id', 'unit_head_id', 'unit_head_user_id', 'user_id'], 'default', 'value' => null],
            [['org_unit_id', 'org_type_id', 'parent_id', 'successor_id', 'unit_head_id', 'unit_head_user_id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'date_created'], 'safe'],
            [['org_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['org_unit_id' => 'unit_id']],
            [['unit_head_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitHead::class, 'targetAttribute' => ['unit_head_id' => 'unit_head_id']],
            [['org_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitType::class, 'targetAttribute' => ['org_type_id' => 'unit_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'org_unit_history_id' => 'Org Unit History ID',
            'org_unit_id' => 'Org Unit ID',
            'org_type_id' => 'Org Type ID',
            'parent_id' => 'Parent ID',
            'successor_id' => 'Successor ID',
            'unit_head_id' => 'Unit Head ID',
            'unit_head_user_id' => 'Unit Head User ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'user_id' => 'User ID',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUnit(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['unit_id' => 'org_unit_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHead(): ActiveQuery
    {
        return $this->hasOne(UnitHead::class, ['unit_head_id' => 'unit_head_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getType(): ActiveQuery
    {
        return $this->hasOne(UnitType::class, ['unit_type_id' => 'org_type_id']);
    }
}
