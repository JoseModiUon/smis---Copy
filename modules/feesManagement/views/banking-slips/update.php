<?php

use yii\helpers\Html;

$this->title = 'Update Banking Slip: ';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Banking Slips', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
/* @var $this yii\web\View */
/* @var $model app\modules\feesManagement\models\BankingSlips */
?>
<div class="banking-slips-update">

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