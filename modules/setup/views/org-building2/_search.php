<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\OrgBuildingSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-building-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'building_id') ?>

    <?= $form->field($model, 'building_code') ?>

    <?= $form->field($model, 'building_name') ?>

    <?= $form->field($model, 'floors') ?>

    <?= $form->field($model, 'study_center') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
