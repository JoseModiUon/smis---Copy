<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankAccounts $model */

$this->title = $model->account_details;
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-accounts-view">
    <div class="card">
        <div class="card-body">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Update', ['update', 'brank_account_id' => $model->brank_account_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'brank_account_id' => $model->brank_account_id], [
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
                    'bank_account',
                    'branch_code',
                    'account_no',
                    'account_details',
                    'account_type',
                    'min_amount',
                    'max_amount',
                    'currency_id',
                    'brank_account_id',
                ],
            ]) ?>
        </div>
    </div>

</div>