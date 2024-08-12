<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\SponsorBeneficiary $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sponsor-beneficiary-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sponsor_beneficiary_id') ?>

    <?= $form->field($model, 'receipt_sponsor_fund_id') ?>

    <?= $form->field($model, 'reg_no') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'trans_type') ?>

    <?php // echo $form->field($model, 'transfer_status') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'post_date') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
