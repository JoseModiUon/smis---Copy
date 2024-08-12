<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_unit_head".
 *
 * @property int $unit_head_id
 * @property string $unit_head_name PRINCIPAL,DIRECTOR,CHAIRMAN
 * @property string $status
 */
class UnitHead extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.org_unit_head';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['unit_head_name'], 'required'],
            [['unit_head_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'unit_head_id' => 'Unit Head ID',
            'unit_head_name' => 'Unit Head Name',
            'status' => 'Status',
        ];
    }
}
