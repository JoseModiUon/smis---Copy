<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_banks".
 *
 * @property string|null $bank_code
 * @property string|null $bank_name
 * @property int $brank_id
 */
class Banks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_banks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_code'], 'string', 'max' => 40],
            [['bank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_code' => 'Bank Code',
            'bank_name' => 'Bank Name',
            'brank_id' => 'Brank ID',
        ];
    }

    public function getBankBranch()
    {
        return $this->hasOne(BankBranches::class, ['bank_code' => 'bank_code']);
    }
}
