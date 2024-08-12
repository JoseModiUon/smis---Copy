<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrOptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-option-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'option_id') ?>

    <?= $form->field($model, 'option_code') ?>

    <?= $form->field($model, 'option_name') ?>

    <?= $form->field($model, 'degree_id') ?>

    <?= $form->field($model, 'option_desc') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'option_status')?>

    <?php // echo $form->field($model, 'progress_type')?>
=======
    <?php // echo $form->field($model, 'option_status') ?>

    <?php // echo $form->field($model, 'progress_type') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $form->field($model, 'option_status') ?>

    <?php // echo $form->field($model, 'progress_type') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
