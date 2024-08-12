<?php

use app\modules\feesManagement\models\BankAccounts;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankAccounts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bank-accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'bank_account')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'account_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'account_details')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'account_type')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'min_amount')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'max_amount')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-6">
            <?php
            $bankName = BankAccounts::find()->select(['currency_id', 'currency_id'])->orderBy([
                'currency_id' => SORT_ASC,
            ])->all();
            $data = ArrayHelper::map($bankName, 'currency_id', 'currency_id');
            echo $form->field($model, 'currency_id')
                ->label('Currency Description', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Currency ID ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>