<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrCourseGroupSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prog-curr-course-group-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'course_group_id') ?>

    <?= $form->field($model, 'course_group_name') ?>

    <?= $form->field($model, 'course_group_desc') ?>

    <?= $form->field($model, 'course_group_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
