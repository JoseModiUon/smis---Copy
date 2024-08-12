<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgrammes;
use app\models\ExGradingSystem;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgrammeCurriculum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-programme-curriculum-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    $progs = OrgProgrammes::find()->select(['prog_id', 'prog_short_name'])->asArray()->all();
                    $data = ArrayHelper::map($progs, 'prog_id', 'prog_short_name');
                    echo $form
                        ->field($model, 'prog_id')
                        ->label('Programme', ['class'=>'mb-2 fw-bold'])
                        ->widget(Select2::classname(), [
                            'data' => $data,
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Programme...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                ?>
            </div>
        </div> 
        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'prog_curriculum_desc')
                    ->textInput(['maxlength' => true])
                    ->label('Programme Curriculum Description', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>


        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'duration')
                    ->textInput(['maxlength' => true])
                    ->label('Duration', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'pass_mark')
                    ->textInput(['maxlength' => true])
                    ->label('Pass Mark', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'annual_semesters')
                    ->textInput(['maxlength' => true])
                    ->label('Annual Semesters', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        
        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'max_units_per_semester')
                    ->textInput(['maxlength' => true])
                    ->label('Max Units Per Semester', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'average_type')
                    ->textInput(['maxlength' => true])
                    ->label('Average Type', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">


                <?=$form->field($model, 'award_rounding')->label(' Award Rounding...',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
                    'data' =>['TRUNCATE' => 'TRUNCATE', 'ROUNDING' => 'ROUNDING'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Award Rounding...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>


        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'start_date')
                    ->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Enter start date ...'],
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
                    ->field($model, 'end_date')
                    ->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Enter end date ...'],
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
                    ->field($model, 'prog_cur_prefix')
                    ->textInput(['maxlength' => true])
                    ->label('Programme Curriculum Prefix', ['class'=>'mb-2 fw-bold'])
                ?>
            </div>
        </div>

        <!--<div class="row mb-2">
            <div class="col-md-12">
<!--                <?//=
////                    $form
////                    ->field($model, 'date_created')
////                    ->widget(DatePicker::classname(), [
////                        'options' => ['placeholder' => 'Enter Date Created ...'],
////                        'pluginOptions' => [
////                            'autoclose' => true,
////                            'format' => 'yyyy-mm-dd'
////                        ]
////                    ]);
////                ?>
            </div>
        </div> -->

        <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    $grading = ExGradingSystem::find()->select(['grading_system_id', 'grading_system_name'])->asArray()->all();
                    $data = ArrayHelper::map($grading, 'grading_system_id', 'grading_system_name');
                    echo $form
                        ->field($model, 'grading_system_id')
                        ->label('Grading System', ['class'=>'mb-2 fw-bold'])
                        ->widget(Select2::classname(), [
                            'data' => $data,
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Grading System...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                ?>
            </div>
        </div> 

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'status')
                    ->label('Status', ['class'=>'mb-2 fw-bold'])
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
    
        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'approval_date')
                    ->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Enter approval date ...'],
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
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>


    <?php ActiveForm::end(); ?>

</div>
