<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\SmWithdrawalType;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
                $withdrawalRequest = SmWithdrawalType::find()
                ->select([
                    'withdrawal_type_id',
                    'withdrawal_type_name'
                ])
                ->where('true')->asArray()->all();
$data = ArrayHelper::map($withdrawalRequest, 'withdrawal_type_id', 'withdrawal_type_name');


echo $form
->field($model, 'withdrawal_type_id')
->label('Withdrawal Type', ['class'=>'mb-2 fw-bold'])
->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Program Withdrawal Type ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-">
            <?=
    $form
    ->field($model, 'request_date')
    ->label('Request Date', ['class'=>'mb-2 fw-bold'])
    ->textInput(['maxlength' => true])
?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-">
            <?=
    $form
    ->field($model, 'reason')
    ->label('Reason', ['class'=>'mb-2 fw-bold'])
    ->textInput(['maxlength' => true])
?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
    $form
    ->field($model, 'approval_status')
    ->label('Approval Status', ['class'=>'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' =>['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
        'language' => 'en',
        'options' => ['placeholder' => 'Select a status...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
        </div>
    </div> 



    <!-- <? $form->field($model, 'student_id')->hiddenInput() ?> -->

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
