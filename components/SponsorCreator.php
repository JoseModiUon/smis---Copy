<?php


namespace app\components;

use app\models\ReceiptSponsorFund;
use app\models\SponsorBeneficiary;
use Yii;
use yii\base\Component;

class SponsorCreator extends Component
{

    public function sponsorCreator($post): SponsorBeneficiary|false
    {

        $model = SponsorBeneficiary::find()->where([
            'receipt_sponsor_fund_id' =>  $post['receipt_sponsor_fund_id'],
            'reg_no' => $post['reg_no']
        ])->one();
        if (!$model) {
            $model = new SponsorBeneficiary();


            $model->load($post);


            $receiptfund = ReceiptSponsorFund::findOne($model->receipt_sponsor_fund_id);

            if ($receiptfund->findBalance() > $model->amount && $receiptfund->findBalance() - $model->amount >= 0) {
                return $model;
            }
            return false;
        }
        return false;
    }

    public function bulkCreator(array $records, array $post): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $beneficiaries = [];

            foreach ($records as $rec) {
                $record = array_merge(['reg_no' => $rec[0], 'amount' => $rec[1], $post]);
                $model = $this->sponsorCreator($record);
                $model->save();
                $model->refresh();
                $beneficiaries[] = $model;
                // $transaction->commit();
            }
            return $beneficiaries;
        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }
    }
}
