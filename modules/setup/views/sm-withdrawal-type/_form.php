<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'withdrawal_type_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
