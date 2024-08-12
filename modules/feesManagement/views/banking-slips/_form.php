<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feesManagement\models\BankingSlips */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banking-slips-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'deposit_amount')
                ->textInput()
                ->label('Deposit Amount ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'reg_number')
                ->textInput()
                ->label('Registration Number ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>