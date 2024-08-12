<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgCoursesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-courses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'course_code') ?>

    <?= $form->field($model, 'course_name') ?>

    <?= $form->field($model, 'level_of_study') ?>

    <?= $form->field($model, 'semester') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'academic_hours')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'org_unit_id')?>

    <?php // echo $form->field($model, 'billing_factor')?>

    <?php // echo $form->field($model, 'category_id')?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php // echo $form->field($model, 'academic_hours') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'org_unit_id') ?>

    <?php // echo $form->field($model, 'billing_factor') ?>

    <?php // echo $form->field($model, 'category_id') ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
