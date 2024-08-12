<?php

namespace app\modules\feesManagement\models;

use app\modules\feesManagement\traits\FeeTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "smis.fss_sponsor_beneficiary".
 *
 * @property int $sponsor_beneficiary_id
 * @property int $receipt_sponsor_fund_id
 * @property string $reg_no
 * @property string|null $name
 * @property string|null $trans_type
 * @property string|null $transfer_status
 * @property float $amount
 * @property string $post_date
 * @property string $user_id
 */
class SponsorBeneficiary extends \yii\db\ActiveRecord
{
    use FeeTrait;
    /**
     * @var UploadedFile
     */
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_sponsor_beneficiary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xls, xlsx'],
            [['receipt_sponsor_fund_id', 'reg_no', 'amount', 'post_date', 'user_id'], 'required'],
            [['receipt_sponsor_fund_id'], 'default', 'value' => null],
            [['receipt_sponsor_fund_id'], 'integer'],
            [['amount'], 'number'],
            [['post_date'], 'safe'],
            [['user_id'], 'string'],
            [['trans_type', 'transfer_status'], 'string', 'max' => 15],
            [['reg_no'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 30],
            [['receipt_sponsor_fund_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReceiptSponsorFund::class, 'targetAttribute' => ['receipt_sponsor_fund_id' => 'receipt_sponsor_fund_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_beneficiary_id' => 'Sponsor Beneficiary ID',
            'receipt_sponsor_fund_id' => 'Receipt Sponsor Fund ID',
            'reg_no' => 'Reg No',
            'name' => 'Name',
            'trans_type' => 'Trans Type',
            'transfer_status' => 'Transfer Status',
            'amount' => 'Amount',
            'post_date' => 'Post Date',
            'user_id' => 'User ID',
        ];
    }


    /**
     * update beneficiary
     *
     * @return bool
     */
    public function updateBeneficiary() :bool
    {
        $post =Yii::$app->request->post();

        if(!array_key_exists('SponsorBeneficiary', $post)) {
            $params = $post['BankingSlips'];
            $arr = [
                'reg_no' => $params['reg_number'],
                'amount' => $params['deposit_amount'],
            ];
            $this->assign($this,$arr);
        }else {
            $this->load($post);
        }
        $receiptfund = ReceiptSponsorFund::findOne($this->receipt_sponsor_fund_id);

        $ok = false;
        if (!$this->isNewRecord) {
            if ($this->getTotalAllocated() + $this->amount < $receiptfund->amount) {
                $ok = $this->save();
            }
        }
        return $ok;
    }
    /**
     * Summation of all allocated funds before a beneficiary
     *
     * @return integer
     */
    public function getTotalAllocated($runningBalance = false): int
    {
        $allFunds = $this->find()->where(['receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id])->orderBy(['sponsor_beneficiary_id' => SORT_ASC])->asArray()->all();
        $data = ArrayHelper::map($allFunds, 'sponsor_beneficiary_id', 'amount');
        $totalAllocated = 0;
        foreach ($data as $sponsor_beneficiary_id => $amount) {

            if ($runningBalance) {
                if ($sponsor_beneficiary_id <= $this->sponsor_beneficiary_id) {
                    $totalAllocated += $amount;
                }
            } else {
                if ($sponsor_beneficiary_id < $this->sponsor_beneficiary_id) {
                    $totalAllocated += $amount;
                }
            }
        }
        return $totalAllocated;
    }

    /**
     * Find the running balance at the interval of every beneficiary
     *
     * @return integer
     */
    public function getRunningBalance(): int
    {
        $totalAllocated = $this->getTotalAllocated(true);
        $totalAmount = ReceiptSponsorFund::findOne($this->receipt_sponsor_fund_id)->amount;
        return $totalAmount - $totalAllocated;
    }


    /**
     * 
     *
     * @return ActiveRecord
     */
    public function getReceipt()
    {
        return $this->hasOne(ReceiptSponsorFund::class, ['receipt_sponsor_fund_id' => 'receipt_sponsor_fund_id']);
    }

        /**
     * 
     *
     * @return ActiveRecord
     */
    public function getBankingSlips()
    {
        return $this->hasOne(BankingSlips::class, ['sponsor_beneficiary_id' => 'sponsor_beneficiary_id']);
    }
}
