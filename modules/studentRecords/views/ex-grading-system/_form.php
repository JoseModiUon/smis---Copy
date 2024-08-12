<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystem $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-grading-system-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row mb-2">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'grading_system_name')
                    ->textInput()
                    ->label('Grading System Name ',['class'=>'mb-2 fw-bold']) 
                ?>

            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'grading_system_desc')
                    ->textInput()
                    ->label('Grading System Desc ',['class'=>'mb-2 fw-bold']) 
                ?>

            </div>
        </div>


                
        <div class="row mb-2">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'grading_system_desc')
                    ->textInput()
                    ->label('grading system description ',['class'=>'mb-2 fw-bold']) 
                ?>

            </div>
        </div>

    <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    echo $form
                        ->field($model, 'status')
                        ->label('Status', ['class'=>'mb-2 fw-bold'])
                        ->widget(Select2::classname(), [
                            'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Status ..'],
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
