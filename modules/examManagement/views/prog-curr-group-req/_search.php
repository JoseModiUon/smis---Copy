<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrGroupRequirementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prog-curr-group-requirement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curr_group_requirement_id') ?>

    <?= $form->field($model, 'prog_curr_level_req_id') ?>

    <?= $form->field($model, 'prog_curr_course_group_id') ?>

    <?= $form->field($model, 'min_group_courses') ?>

    <?= $form->field($model, 'group_pass_type') ?>

    <?php // echo $form->field($model, 'min_group_pass')?>

    <?php // echo $form->field($model, 'gpa_pass_type')?>

    <?php // echo $form->field($model, 'gpa_courses')?>

    <?php // echo $form->field($model, 'extra_courses_processing')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
