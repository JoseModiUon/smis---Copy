<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgrammeCurriculumSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-programme-curriculum-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curriculum_id') ?>

    <?= $form->field($model, 'prog_id') ?>

    <?= $form->field($model, 'prog_curriculum_desc') ?>

    <?= $form->field($model, 'duration') ?>

    <?= $form->field($model, 'pass_mark') ?>

    <?php // echo $form->field($model, 'annual_semesters') ?>

    <?php // echo $form->field($model, 'max_units_per_semester') ?>

    <?php // echo $form->field($model, 'average_type') ?>

    <?php // echo $form->field($model, 'award_rounding') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'prog_cur_prefix') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'grading_system_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'approval_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
