<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\OrgProgCurrCourseSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curriculum_course_id') ?>

    <?= $form->field($model, 'prog_curriculum_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'credit_factor') ?>

    <?= $form->field($model, 'credit_hours') ?>

    <?php // echo $form->field($model, 'level_of_study')?>

    <?php // echo $form->field($model, 'semester')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'has_course_work')->checkbox()?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
