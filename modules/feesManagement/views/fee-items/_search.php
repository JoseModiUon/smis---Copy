<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\FeeItemsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fee-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fee_code') ?>

    <?= $form->field($model, 'fee_description') ?>

    <?= $form->field($model, 'priority') ?>

    <?= $form->field($model, 'naration') ?>

    <?= $form->field($model, 'fee_type') ?>

    <?php // echo $form->field($model, 'publish') ?>

    <?php // echo $form->field($model, 'fee_code_alias') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
