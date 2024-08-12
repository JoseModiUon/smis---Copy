<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$request = Yii::$app->request;

$get = $request->get('sponsor_id');

// dd($get);

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sponsor-beneficiary-form" style="margin: 4px, 4px;padding: 4px;height: 500px;overflow-x: hidden;overflow-y: auto;text-align: justify;">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'receipt_sponsor_fund_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'reg_no')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'trans_type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'transfer_status')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'amount')->textInput() ?>
    <?= $form->field($model, 'post_date')->textInput() ?>
    <?= $form->field($model, 'user_id')->textInput() ?>
    <?= $form->field($model, 'file_path')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>