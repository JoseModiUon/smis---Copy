<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\StudentSemSessionProgress $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="student-sem-session-progress-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'student_semester_session_id') ?>

    <?= $form->field($model, 'registration_date') ?>

    <?= $form->field($model, 'academic_progress_id') ?>

    <?= $form->field($model, 'sem_progress_number') ?>

    <?= $form->field($model, 'billable') ?>

    <?php // echo $form->field($model, 'rep_status_id') ?>

    <?php // echo $form->field($model, 'prom_status_id') ?>

    <?php // echo $form->field($model, 'reporting_snyc_status')->checkbox() ?>

    <?php // echo $form->field($model, 'semester_progress') ?>

    <?php // echo $form->field($model, 'promotion_status') ?>

    <?php // echo $form->field($model, 'prog_curriculum_semester_id') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'ip_address') ?>

    <?php // echo $form->field($model, 'user_action') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
