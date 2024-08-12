<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExMarksheet $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-marksheet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'marksheet_id')->textInput() ?>

    <?= $form->field($model, 'student_course_reg_id')->textInput() ?>

    <?= $form->field($model, 'course_work_mark')->textInput() ?>

    <?= $form->field($model, 'exam_mark')->textInput() ?>

    <?= $form->field($model, 'final_mark')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
