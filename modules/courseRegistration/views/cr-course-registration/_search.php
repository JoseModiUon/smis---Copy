<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\CrCourseRegistrationSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-course-registration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // $form->field($model, 'student_course_reg_id') ?>

    <?php //echo  $form->field($model, 'timetable_id') ?>

    <?php // $form->field($model, 'student_semester_session_id') ?>

    <?php // $form->field($model, 'course_registration_type_id') ?>

    <?php // $form->field($model, 'registration_date') ?>

    <?php // echo $form->field($model, 'course_reg_status_id') ?>

    <?php // echo $form->field($model, 'source_ipaddress') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'class_code') ?>

    <?= $form->field($model, 'registration_number') ?>

    <div class="form-group">
        <br>
        <?= Html::submitButton('Search', ['class' => 'btn btn-outline-primary']) ?>
        <?php // Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
