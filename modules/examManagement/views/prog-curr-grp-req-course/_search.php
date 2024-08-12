<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrGroupReqCourseSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prog-curr-group-req-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'prog_curr_group_req_course_id') ?>

    <?= $form->field($model, 'prog_curr_group_requirement_id') ?>

    <?= $form->field($model, 'prog_curriculum_course_id') ?>

    <?= $form->field($model, 'credit_factor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
