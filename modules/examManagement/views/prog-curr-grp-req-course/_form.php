<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupReqCourse $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prog-curr-group-req-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prog_curr_group_requirement_id')->textInput() ?>

    <?= $form->field($model, 'prog_curriculum_course_id')->textInput() ?>

    <?= $form->field($model, 'credit_factor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
