<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\BankAccountsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bank-accounts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bank_account') ?>

    <?= $form->field($model, 'branch_code') ?>

    <?= $form->field($model, 'account_no') ?>

    <?= $form->field($model, 'account_details') ?>

    <?= $form->field($model, 'account_type') ?>

    <?php // echo $form->field($model, 'min_amount') ?>

    <?php // echo $form->field($model, 'max_amount') ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <?php // echo $form->field($model, 'brank_account_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
