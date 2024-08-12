<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\OrgStudyGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-study-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'study_group_name')->textInput(['maxlength' => true])->label('Study Group Name',['class'=>'mb-2 fw-bold']) ?>

<!--    <?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'status')->label('Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
    'data' =>['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
    'language' => 'en',
    'options' => ['placeholder' => 'Select a status...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
