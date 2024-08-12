<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrgSemesterType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-semester-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sem_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
