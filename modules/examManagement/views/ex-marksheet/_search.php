<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\ExMarksheetSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-marksheet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'marksheet_id') ?>

    <?= $form->field($model, 'student_course_reg_id') ?>

    <?= $form->field($model, 'course_work_mark') ?>

    <?= $form->field($model, 'exam_mark') ?>

    <?= $form->field($model, 'final_mark') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
