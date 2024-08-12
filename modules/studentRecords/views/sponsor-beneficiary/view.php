<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Beneficiaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sponsor-beneficiary-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id], [
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
            'sponsor_beneficiary_id',
            'receipt_sponsor_fund_id',
            'reg_no',
            'name',
            'trans_type',
            'transfer_status',
            'amount',
            'post_date',
            'user_id',
            'file_path',
        ],
    ]) ?>

</div>
