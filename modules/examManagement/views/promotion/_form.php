<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmExamResult $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-exam-result-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_registration_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ext_result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level_of_study')->textInput() ?>

    <?= $form->field($model, 'total_marks')->textInput() ?>

    <?= $form->field($model, 'courses_taken')->textInput() ?>

    <?= $form->field($model, 'gpa_courses')->textInput() ?>

    <?= $form->field($model, 'levelTrail')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'gpa')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
