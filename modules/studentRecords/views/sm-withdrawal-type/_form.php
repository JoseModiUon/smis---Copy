<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'withdrawal_type_name')
                ->textInput(['maxlength' => true])
                ->label('Withdrawal Type Name', ['class' => 'mb-2 fw-bold'])
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>