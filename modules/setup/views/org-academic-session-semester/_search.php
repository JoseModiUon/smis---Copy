<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgAcademicSessionSemesterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-academic-session-semester-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'acad_session_semester_id') ?>

    <?= $form->field($model, 'acad_session_id') ?>

    <?= $form->field($model, 'semester_code') ?>

    <?= $form->field($model, 'acad_session_semester_desc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
