<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicLevels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-academic-levels-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row mb-2">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'academic_level')
                    ->textInput()
                    ->label('Level ',['class'=>'mb-2 fw-bold']) 
                ?>

            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'academic_level_name')
                    ->textInput(['maxlength' => true])
                    ->label('Name',['class'=>'mb-2 fw-bold']) 
                ?>
            </div>
        </div>
        
      
        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'academic_level_order')
                    ->textInput()
                    ->label('Order',['class'=>'mb-2 fw-bold']) 
                ?>
            </div>
        </div>  

        <div class="row mb-2">
            <div class="col-md-12">
                <?= 
                    $form
                    ->field($model, 'status')
                    ->label('Status',['class'=>'mb-2 fw-bold'])
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
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-2']) ?>
                </div>
            </div>
        </div>  

    <?php ActiveForm::end(); ?>

</div>
