<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SmNameChangeApprovalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-approval-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name_change_approval_id') ?>

    <?= $form->field($model, 'name_change_id') ?>

    <?= $form->field($model, 'approval_status') ?>

    <?= $form->field($model, 'remarks') ?>

    <?= $form->field($model, 'approved_by') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'approval_date')?>
=======
    <?php // echo $form->field($model, 'approval_date') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $form->field($model, 'approval_date') ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
