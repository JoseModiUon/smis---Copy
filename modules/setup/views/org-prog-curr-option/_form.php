<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgrammeCurriculum;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= 
                $form
                ->field($model, 'option_code')
                ->textInput(['maxlength' => true])
                ->label('Option Code',['class'=>'mb-2 fw-bold']) 
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= 
                $form
                ->field($model, 'option_name')
                ->textInput(['maxlength' => true])
                ->label('Option Name',['class'=>'mb-2 fw-bold']) 
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $curr = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'prog_curriculum_desc'])->asArray()->all();
            $data = ArrayHelper::map($curr, 'prog_curriculum_id', 'prog_curriculum_desc');
            echo $form
                ->field($model, 'prog_cur_id')
                ->label('Course', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Curriculum ...'],
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
                ->field($model, 'option_desc')
                ->textInput(['maxlength' => true])
                ->label('Option Description',['class'=>'mb-2 fw-bold']) 

            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= 
                $form
                ->field($model, 'option_status')
                ->label('Option Status',['class'=>'mb-2 fw-bold'])
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
                ->field($model, 'progress_type')
                ->textInput(['maxlength' => true])
                ->label('Progress Type',['class'=>'mb-2 fw-bold']) 

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
