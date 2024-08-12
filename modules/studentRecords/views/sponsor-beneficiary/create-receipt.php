<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */
$request = Yii::$app->request;

$receipt = $request->get('receipt_sponsor_fund_id');
$sponsor = $request->get('sponsor_id');
$this->title = 'Create Sponsor Beneficiary';
$this->params['breadcrumbs'][] = ['label' => 'View Beneficiaries', 'url' => ['receipt-sponsor-fund/beneficiaries', 'receipt_sponsor_fund_id' => $receipt, 'sponsor_id' => $sponsor]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-beneficiary-create" style="margin: 4px, 4px;padding: 4px;height: 500px;overflow-x: hidden;overflow-y: auto;text-align: justify;">

    <div class="card">
        <div class="card-body">
            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form_custom', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>