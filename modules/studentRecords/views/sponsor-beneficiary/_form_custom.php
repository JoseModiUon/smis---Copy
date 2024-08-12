<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\DateParts;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$request = Yii::$app->request;

$receipt = $request->get('receipt_sponsor_fund_id');

// dd($receipt);

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */
/** @var yii\widgets\ActiveForm $form */

$allRegNos = \yii\helpers\ArrayHelper::map(\app\models\StudentProgrammeCurriculum::find()->all(), 'student_prog_curriculum_id', 'registration_number');

$trans = \yii\helpers\ArrayHelper::map(\app\models\SponsorBeneficiary::find()->all(), 'trans_type', 'trans_type');

?>

<div class="sponsor-beneficiary-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'receipt_sponsor_fund_id')->hiddenInput(['value' => $receipt])->label(false) ?>


    <?php $form->field($model, 'reg_no')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'reg_no')->widget(\kartik\select2\Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map($allRegNos, function ($element) {
            return $element;
        }, function ($element) {
            return $element;
        }),
        'language' => 'en',
        'options' => ['placeholder' => 'Select reg_no ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>



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

    <?= $form->field($model, 'transfer_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?php
    echo $form->field($model, 'post_date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter Receipt date...'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]);
    ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false) ?>

    <?= $form->field($model, 'file_path')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>