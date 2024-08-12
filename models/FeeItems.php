<?php

namespace app\models;

use Yii;

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
class FeeItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_fee_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_code', 'priority', 'fee_code_alias'], 'integer'],
            [['fee_description'], 'string', 'max' => 50],
            [['naration', 'fee_code_alias'], 'string', 'max' => 150],
            [['fee_type'], 'string', 'max' => 15],
            [['publish'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fee_code' => 'Fee Code',
            'fee_description' => 'Fee Description',
            'priority' => 'Priority',
            'naration' => 'Naration',
            'fee_type' => 'Fee Type',
            'publish' => 'Publish',
            'fee_code_alias' => 'Fee Code Alias'
        ];
    }
}
