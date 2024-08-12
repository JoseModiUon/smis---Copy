<?php

namespace app\modules\feesManagement\models;


use Yii;

/**
 * This is the model class for table "smis.fss_bank_accounts".
 *
 * @property string|null $bank_account
 * @property string|null $branch_code
 * @property string|null $account_no
 * @property string|null $account_details
 * @property string|null $account_type
 * @property string|null $min_amount
 * @property string|null $max_amount
 * @property string|null $currency_id
 * @property int $brank_account_id
 */
class BankAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_bank_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_account', 'branch_code', 'account_no', 'account_details', 'account_type', 'min_amount', 'max_amount', 'currency_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_account' => 'Bank Account',
            'branch_code' => 'Branch Code',
            'account_no' => 'Account No',
            'account_details' => 'Account Details',
            'account_type' => 'Account Type',
            'min_amount' => 'Min Amount',
            'max_amount' => 'Max Amount',
            'currency_id' => 'Currency ID',
            'brank_account_id' => 'Brank Account ID',
        ];
    }
    public function getBranch()
    {
        return $this->hasOne(BankBranches::class, ['branch_code' => 'branch_code']);
    }

    public function getBank() 
    {
        return $this->getBranch()->one()->getBank();
    }


}
