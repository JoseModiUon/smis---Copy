<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-unit-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'unit_code')->textInput(['maxlength' => true])->label('Unit Code', ['class' => 'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'unit_name')->textInput(['maxlength' => true])->label('Unit Name', ['class' => 'mb-2 fw-bold']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>