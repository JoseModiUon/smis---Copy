<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_change_id')->textInput() ?>

    <?= $form->field($model, 'request_date')->textInput() ?>

    <?= $form->field($model, 'student_id')->textInput() ?>

    <?= $form->field($model, 'new_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new_othernames')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'document_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_othernames')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
