<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgUnitHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-unit-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'org_unit_history_id') ?>

    <?= $form->field($model, 'org_unit_id') ?>

    <?= $form->field($model, 'org_type_id') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'successor_id') ?>

    <?php // echo $form->field($model, 'unit_head_id') ?>

    <?php // echo $form->field($model, 'unit_head_user_id') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
