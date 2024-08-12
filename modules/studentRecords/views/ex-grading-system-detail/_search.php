<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\ExGradingSystemDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-grading-system-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'grading_system_detail_id') ?>

    <?= $form->field($model, 'grading_system_id') ?>

    <?= $form->field($model, 'lower_bound') ?>

    <?= $form->field($model, 'upper_bound') ?>

    <?= $form->field($model, 'grade') ?>

    <?php // echo $form->field($model, 'grade_point') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'legend') ?>

    <?php // echo $form->field($model, 'extlegend') ?>

    <?php // echo $form->field($model, 'recomm_id') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'ip_address') ?>

    <?php // echo $form->field($model, 'user_action') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
