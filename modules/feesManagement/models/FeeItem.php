<?php

namespace app\modules\feesManagement\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smis.fss_fee_items".
 *
 * @property int $fee_code
 * @property string|null $fee_description
 * @property int|null $priority
 * @property string|null $naration
 * @property string|null $fee_type
 * @property string|null $publish
 */
class FeeItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.fss_fee_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fee_description'], 'string', 'max' => 50],
            [['naration'], 'string', 'max' => 150],
            [['fee_type'], 'string', 'max' => 15],
            [['publish'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fee_description' => 'Fee Description',
            'priority' => 'Priority',
            'naration' => 'Naration',
            'fee_type' => 'Fee Type',
            'publish' => 'Publish',
        ];
    }
}
