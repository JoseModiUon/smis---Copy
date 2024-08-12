<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgUnitCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-unit-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'org_unit_course_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'org_unit_id') ?>

    <?= $form->field($model, 'org_teaching_id') ?>

    <?= $form->field($model, 'start_date') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'end_date')?>

    <?php // echo $form->field($model, 'user_id')?>
=======
    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'user_id') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'user_id') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
