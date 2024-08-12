<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrOptionCoursesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-option-courses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'option_course_id') ?>

    <?= $form->field($model, 'option_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'prog_cur_id') ?>

    <?= $form->field($model, 'course_type') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'degree_code')?>
=======
    <?php // echo $form->field($model, 'degree_code') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $form->field($model, 'degree_code') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
