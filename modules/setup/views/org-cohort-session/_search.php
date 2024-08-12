<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgCohortSessionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-cohort-session-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cohort_session_id') ?>

    <?= $form->field($model, 'cohort_session_name') ?>

    <?= $form->field($model, 'cohort_id') ?>

    <?= $form->field($model, 'prog_curriculum_semester_id') ?>

    <?= $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
