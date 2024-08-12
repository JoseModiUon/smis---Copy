<?php

use app\models\OrgAcademicSession;
use app\models\SmStudentSponsor;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ReceiptSponsorFund $model */
/** @var yii\widgets\ActiveForm $form */
$request = Yii::$app->request;

$get = $request->get('sponsor_id');
?>

<div class="receipt-sponsor-fund-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sponsor_id')->hiddenInput(['value' => $get])->label(false) ?>



    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'amount')
                ->textInput()
                ->label('Amount ', ['class' => 'mb-2 fw-bold'])
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $data = ['POST' => 'POST', 'CANCEL' => 'CANCEL'];
            echo $form
                ->field($model, 'trans_type')
                ->label('Trans Type', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Trans Type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'description')
                ->textarea(['rows' => 3])
                ->label('Description ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'receipt_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Select Receipt Date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'pv_no')
                ->textInput(['maxinput' => true])
                ->label('Payment Voucher No. ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'cheque_no')
                ->textInput(['maxinput' => true])
                ->label('Cheque Number ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>




    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $sp = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'acad_session_id', 'acad_session_name');
            echo $form
                ->field($model, 'academic_session')
                ->label('Academic Session', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Academic Session ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'user_id')->hiddenInput([
        'value' => Yii::$app->user->identity->username
    ])->label(false) ?>


    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'post_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Select Post Date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'no_of_beneficiaries')
                ->textInput(['maxInput' => true])
                ->label('No. of Beneficiaries ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'source_reference')
                ->textInput()
                ->label('Source Reference ', ['class' => 'mb-2 fw-bold'])
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