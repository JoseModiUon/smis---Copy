<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_unit".
 *
 * @property int $unit_id
 * @property string $unit_code
 * @property string $unit_name
 */
class Unit extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['unit_code', 'unit_name'], 'required'],
            [['unit_code'], 'string', 'max' => 6],
            [['unit_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'unit_id' => 'Unit ID',
            'unit_code' => 'Unit Code',
            'unit_name' => 'Unit Name',
        ];
    }
}
