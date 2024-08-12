<?php

use app\models\generated\Country;
use app\models\generated\Sponsor;
use app\models\OrgCountry;
use kartik\date\DatePicker;
use kartik\label\LabelInPlace;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Student */
/* @var $form yii\widgets\ActiveForm */

$config = ['template' => "{input}\n{error}\n{hint}"];
?>

<div class="bg-primary bg-opacity-10 border-primary border border-2 border-opacity-25 rounded">
    <div class="d-flex flex-md-row justify-content-between align-items-center m-3">


        <?php $form = ActiveForm::begin([
            'id' => 'a-form',
            'fieldConfig' => [
                'template' => '<div class="">{input}{error}</div>',
                'labelOptions' => ['class' => false, 'tag' => false,],
                'inputOptions' => ['class' => 'form-control', 'tag' => false, 'placeholder' => 'Needed'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
        ]); ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'student_number', $config)->widget(LabelInPlace::class); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'surname', $config)->widget(LabelInPlace::class); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'other_names', $config)->widget(LabelInPlace::class); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'gender')->widget(Select2::class, [
                    'data' => ['M' => 'Male', 'F' => 'Female'],
                    'options' => ['placeholder' => 'Select a Gender'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'country_code')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        OrgCountry::find()->orderBy('nationality')->asArray()->all(),
                        'country_code',
                        'nationality'
                    ),
                    'options' => ['placeholder' => 'Select a Nationality'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'date_of_birth')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'select birth date ...'],
                    'pickerIcon' => '<i class="bi bi-calendar2-week text-primary"></i>',
                    'removeIcon' => '<i class="bi bi-x-octagon-fill text-danger"></i>',
                    'pluginOptions' => [
                        'format' => 'dd-M-yyyy',
                        'autoclose' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'id_no', $config)->widget(LabelInPlace::class); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'passport_no', $config)->widget(LabelInPlace::class); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>