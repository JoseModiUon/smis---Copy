<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ReceiptSponsorFund $model */

$this->title = $model->receipt_sponsor_fund_id;
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Funds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="receipt-sponsor-fund-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'receipt_sponsor_fund_id',
            'sponsor_id',
            'amount',
            'trans_type',
            'description',
            'receipt_date',
            'pv_no',
            'cheque_no',
            'academic_session',
            'user_id',
            'post_date',
            'no_of_beneficiaries',
            'source_reference',
        ],
    ]) ?>

</div>
