<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExMode $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-mode-form">

    <?php $form = ActiveForm::begin(); ?>
 <div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'exam_mode_name')->textInput(['maxlength' => true]) ?>
		</div>
</div>  
<div class="row mb-2">
        <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
  </div>

    <?php ActiveForm::end(); ?>

</div>
