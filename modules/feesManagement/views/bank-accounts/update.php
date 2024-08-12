<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankAccounts $model */

$this->title = 'Update Bank Accounts: ' . $model->account_details;
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->account_details, 'url' => ['view', 'brank_account_id' => $model->brank_account_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-accounts-update">


    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>