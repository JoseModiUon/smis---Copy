<?php

namespace app\modules\feesManagement\models;

use app\models\SmStudentSponsor;
use Yii;

/**
 * This is the model class for table "smis.fss_receipt_sponsor_fund".
 *
 * @property int $receipt_sponsor_fund_id
 * @property int $sponsor_id
 * @property float $amount
 * @property string $trans_type
 * @property string|null $description
 * @property string $receipt_date
 * @property string|null $pv_no
 * @property string|null $cheque_no
 * @property string|null $academic_session
 * @property string $user_id
 * @property string $post_date
 * @property int $no_of_beneficiaries
 * @property string|null $source_reference
 */
class ReceiptSponsorFund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.fss_receipt_sponsor_fund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_id', 'amount', 'trans_type', 'receipt_date', 'user_id', 'post_date', 'no_of_beneficiaries', 'source_reference'], 'required'],
            [['sponsor_id', 'no_of_beneficiaries'], 'default', 'value' => null],
            [['receipt_sponsor_fund_id', 'sponsor_id', 'no_of_beneficiaries'], 'integer'],
            [['amount'], 'number'],
            [['receipt_date', 'post_date'], 'safe'],
            [['trans_type', 'user_id', 'source_reference'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 150],
            [['no_of_beneficiaries'], 'integer', 'max' => 1000],
            [['pv_no', 'cheque_no'], 'string', 'max' => 15],
            [['academic_session'], 'string', 'max' => 20],
            [['receipt_sponsor_fund_id'], 'unique'],
            [['sponsor_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmStudentSponsor::class, 'targetAttribute' => ['sponsor_id' => 'sponsor_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'receipt_sponsor_fund_id' => 'Receipt Sponsor Fund ID',
            'sponsor_id' => 'Sponsor ID',
            'amount' => 'Amount',
            'trans_type' => 'Trans Type',
            'description' => 'Description',
            'receipt_date' => 'Receipt Date',
            'pv_no' => 'Pv No',
            'cheque_no' => 'Cheque No',
            'academic_session' => 'Academic Session',
            'user_id' => 'User ID',
            'post_date' => 'Post Date',
            'no_of_beneficiaries' => 'No Of Beneficiaries',
            'source_reference' => 'Source Reference',
        ];
    }

    /**
     * Gets query for [[AcademicLevel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSponsor()
    {
        return $this->hasOne(SmStudentSponsor::className(), ['sponsor_id' => 'sponsor_id']);
    }


    /**
     * Calculate Receipt Sponsor Balance
     *
     * @return integer
     */
    public function findBalance(): int
    {
        $total_amount = $this->findAllocated();
        return $this->amount - $total_amount;
    }


    
    /**
     * Calculate total allocated
     *
     * @return integer
     */
    public function findAllocated(): int
    {
        $beneficiaries = SponsorBeneficiary::find()->where(['receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id])->all();
        return array_reduce($beneficiaries, fn ($sum, $item) => $sum += $item->amount, 0);
    }


    public function getTotalBeneficiaries(): int
    {
        $beneficiaries = SponsorBeneficiary::find()->where(['receipt_sponsor_fund_id' => $this->receipt_sponsor_fund_id])->all();
        return count($beneficiaries);
    }

    public function getBalance()
    {
        $sum = Yii::$app->db->createCommand('SELECT SUM(amount) FROM smis.fss_sponsor_beneficiary where receipt_sponsor_fund_id=:receiptId')
            ->bindValue(':receiptId', $this->receipt_sponsor_fund_id)
            ->queryScalar();

        $balance = ($this->amount) - $sum;
        return $balance;
    }


    public function getReceiptId()
    {
        return $this->receipt_sponsor_fund_id;
    }

    public function countBeneficiaries()
    {
        $count = Yii::$app->db->createCommand('SELECT COUNT(amount) FROM smis.fss_sponsor_beneficiary where receipt_sponsor_fund_id=:receiptId')
            ->bindValue(':receiptId', $this->receipt_sponsor_fund_id)
            ->queryScalar();
        return $count;
    }
}
