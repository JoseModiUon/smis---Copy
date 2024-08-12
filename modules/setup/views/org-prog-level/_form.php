<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgLevel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-level-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--    <? //= $form->field($model, 'programme_level_id')->textInput() 
            ?>-->


    <?= $form->field($model, 'programme_level_name')->textInput(['maxlength' => true])->label('Programme Level Name', ['class' => 'mb-2 fw-bold']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>