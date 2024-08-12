<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalApprovalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-approval-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'withdrawal_approval_id ') ?>

    <?= $form->field($model, 'withdrawal_request_id ') ?>

    <?= $form->field($model, 'approver_id') ?>

    <?= $form->field($model, 'comments ') ?>

    <?= $form->field($model, 'approval_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
