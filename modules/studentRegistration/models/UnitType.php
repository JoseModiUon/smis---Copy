<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_unit_types".
 *
 * @property int $unit_type_id
 * @property string $unit_type_name
 * @property string $status
 */
class UnitType extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_unit_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['unit_type_name'], 'required'],
            [['unit_type_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'unit_type_id' => 'Unit Type ID',
            'unit_type_name' => 'Unit Type Name',
            'status' => 'Status',
        ];
    }
}
