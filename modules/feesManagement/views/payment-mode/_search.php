<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\PaymentModeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payment-mode-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mode_code') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'mode_flag') ?>

    <?= $form->field($model, 'payment_mode_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
