<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\BanksSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bank_code') ?>

    <?= $form->field($model, 'bank_name') ?>

    <?= $form->field($model, 'bank_branch') ?>

    <?= $form->field($model, 'account_no') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'currency_id') 
    ?>

    <?php // echo $form->field($model, 'brank_id') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>