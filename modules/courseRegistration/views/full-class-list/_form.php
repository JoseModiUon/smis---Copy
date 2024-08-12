<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\CrProgCurrTimetable $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-prog-curr-timetable-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'timetable_id')->textInput() ?>

    <?= $form->field($model, 'prog_curriculum_course_id')->textInput() ?>

    <?= $form->field($model, 'prog_curriculum_sem_group_id')->textInput() ?>

    <?= $form->field($model, 'exam_date')->textInput() ?>

    <?= $form->field($model, 'exam_venue')->textInput() ?>

    <?= $form->field($model, 'exam_mode')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
