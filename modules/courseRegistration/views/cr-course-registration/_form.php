<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CrCourseRegistration $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-course-registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= 
            $form->field($model, 'student_course_reg_id')
            ->label('Cohort Description', ['class'=>'mb-2 fw-bold'])
            ->textInput(['maxlength' => true])
            ?>
        </div>
    </div>


    <?= $form->field($model, 'timetable_id')->textInput() ?>

    <?= $form->field($model, 'student_semester_session_id')->textInput() ?>

    <?= $form->field($model, 'course_registration_type_id')->textInput() ?>

    <?= $form->field($model, 'registration_date')->textInput() ?>

    <?= $form->field($model, 'course_reg_status_id')->textInput() ?>

    <?= $form->field($model, 'source_ipaddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userid')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
