<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\ReceiptSponsorFundSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="receipt-sponsor-fund-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'receipt_sponsor_fund_id') ?>

    <?= $form->field($model, 'sponsor_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'trans_type') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'receipt_date') ?>

    <?php // echo $form->field($model, 'pv_no') ?>

    <?php // echo $form->field($model, 'cheque_no') ?>

    <?php // echo $form->field($model, 'academic_session') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'post_date') ?>

    <?php // echo $form->field($model, 'no_of_beneficiaries') ?>

    <?php // echo $form->field($model, 'source_reference') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
