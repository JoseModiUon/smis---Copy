<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\SmApprover $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-approver-form">

    <?php $form = ActiveForm::begin(); ?>

   

    <?= $form->field($model, 'approver')->textInput() ?>

    <?= $form->field($model, 'level')->textInput() ?>

    
	 <?php
    echo $form->field($model, 'status')->label('Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
        'data' =>['ACTIVE' => 'ACTIVE','INACTIVE' => 'INACTIVE',],
        'language' => 'en',
        'options' => ['placeholder' => 'Select Status...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
