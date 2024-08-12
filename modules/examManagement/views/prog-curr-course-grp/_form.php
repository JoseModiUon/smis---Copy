<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrCourseGroup $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">

        <div class="card card-primary card-outline">
            <div class="card-body">
<div class="prog-curr-course-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_group_name')->textInput(['maxlength' => true])->label('Course Group Name'); ?>

    <?= $form->field($model, 'course_group_desc')->textInput(['maxlength' => true])->label('Course Group Description') ?>

    <?= $form->field($model, 'course_group_type')->textInput(['maxlength' => true])->label('Course Group Type') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
</div>
