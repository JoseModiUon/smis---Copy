<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\search\FullClassListSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-prog-curr-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'timetable_id') ?>

    <?= $form->field($model, 'prog_curriculum_course_id') ?>

    <?= $form->field($model, 'prog_curriculum_sem_group_id') ?>

    <?= $form->field($model, 'exam_date') ?>

    <?= $form->field($model, 'exam_venue') ?>

    <?php // echo $form->field($model, 'exam_mode') ?>

    <?php // echo $form->field($model, 'mrksheet_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
