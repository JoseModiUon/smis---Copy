<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgAcademicLevelsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-academic-levels-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'academic_level_id') ?>

    <?= $form->field($model, 'academic_level') ?>

    <?= $form->field($model, 'academic_level_name') ?>

    <?= $form->field($model, 'academic_level_order') ?>

    <?= $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
