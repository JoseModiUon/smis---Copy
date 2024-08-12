<?php

namespace app\modules\feesManagement\models;

use Yii;

/**
 * This is the model class for table "smis.fss_bank_branches".
 *
 * @property string|null $branch_code
 * @property string|null $branch_name
 * @property string|null $bank_code
 * @property int $branch_id
 */
class BankBranches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_bank_branches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_code', 'bank_code'], 'string', 'max' => 40],
            [['branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branch_code' => 'Branch Code',
            'branch_name' => 'Branch Name',
            'bank_code' => 'Bank Code',
            'branch_id' => 'Branch ID',
        ];
    }

    public function getBank()
    {
        return $this->hasOne(Banks::class, ['bank_code' => 'bank_code']);
    }

    public function getBankAccount()
    {
        return $this->hasOne(BankAccounts::class, ['branch_code' => 'branch_code']);
    }
}
