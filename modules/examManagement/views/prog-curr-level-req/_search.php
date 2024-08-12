<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrLevelRequirementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prog-curr-level-requirement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curr_level_req_id') ?>

    <?= $form->field($model, 'prog_curriculum_id') ?>

    <?= $form->field($model, 'prog_study_level') ?>

    <?= $form->field($model, 'min_courses_taken') ?>

    <?= $form->field($model, 'pass_type') ?>

    <?php // echo $form->field($model, 'min_pass_courses')?>

    <?php // echo $form->field($model, 'gpa_choice')?>

    <?php // echo $form->field($model, 'gpa_courses')?>

    <?php // echo $form->field($model, 'gpa_weight')?>

    <?php // echo $form->field($model, 'pass_result')?>

    <?php // echo $form->field($model, 'pass_recommendation')?>

    <?php // echo $form->field($model, 'fail_type')?>

    <?php // echo $form->field($model, 'fail_result')?>

    <?php // echo $form->field($model, 'fail_recommendation')?>

    <?php // echo $form->field($model, 'incomplete_result')?>

    <?php // echo $form->field($model, 'incomplete_recommendation')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
