<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrSemesterGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-semester-group-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curriculum_sem_group_id') ?>

    <?= $form->field($model, 'prog_curriculum_semester_id') ?>

    <?= $form->field($model, 'study_centre_group_id') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'end_date') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'registration_deadline')?>

    <?php // echo $form->field($model, 'display_date')?>

    <?php // echo $form->field($model, 'programme_level')?>

    <?php // echo $form->field($model, 'status')?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php // echo $form->field($model, 'registration_deadline') ?>

    <?php // echo $form->field($model, 'display_date') ?>

    <?php // echo $form->field($model, 'programme_level') ?>

    <?php // echo $form->field($model, 'status') ?>
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
