<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmStudentSponsor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-student-sponsor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'sponsor_id')->textInput() 
    ?>

    <?= $form->field($model, 'sponsor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sponsor_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>