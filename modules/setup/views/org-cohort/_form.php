<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-cohort-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'cohort_desc')
                ->label('Cohort Description', ['class'=>'mb-2 fw-bold'])
                ->textInput(['maxlength' => true])

            ?>

        </div>
    </div>
    
    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
           ->field($model, 'cohort_year')
           ->label('Cohort Year', ['class'=>'mb-2 fw-bold'])
           ->textInput(['maxlength' => true]) 
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'adm_start_date')
                ->label('Adm Start Date', ['class'=>'mb-2 fw-bold'])
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter adm start date ...'],
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
            <?=
            $form
                ->field($model, 'adm_end_date')
                ->label('Adm End Date', ['class'=>'mb-2 fw-bold'])
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter adm end date ...'],
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
            <?= 
                $form
                ->field($model, 'cohort_status')
                ->label('Cohort Status', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select a status...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);

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
