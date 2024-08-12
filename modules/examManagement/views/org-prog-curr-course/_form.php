<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\OrgProgCurrCourse $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-prog-curr-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prog_curriculum_id')->textInput() ?>

    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'credit_factor')->textInput() ?>

    <?= $form->field($model, 'credit_hours')->textInput() ?>

    <?= $form->field($model, 'level_of_study')->textInput() ?>

    <?= $form->field($model, 'semester')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'has_course_work')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
