<?php

use app\models\OrgCourses;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgCurrOption;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOptionCourses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-option-courses-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $options = OrgProgCurrOption::find()->select(['option_id', 'option_name'])->asArray()->all();
            $data = ArrayHelper::map($options, 'option_id', 'option_name');
            echo $form
                ->field($model, 'option_id')
                ->label('Option Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Option Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $courses = OrgCourses::find()->select(['course_id', 'course_name'])->asArray()->all();
            $data = ArrayHelper::map($courses, 'course_id', 'course_name');
            echo $form
                ->field($model, 'course_id')
                ->label('Course Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Course Name ...'],
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
                ->field($model, 'course_type')
                ->textInput(['maxlength' => true])
                ->label('Course Type',['class'=>'mb-2 fw-bold']) 
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
