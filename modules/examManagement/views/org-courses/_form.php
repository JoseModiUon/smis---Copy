<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\OrgUnit;
use app\models\CrCourseCategory;
use app\models\OrgAcademicLevels;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCourses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-courses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_code')->textInput(['maxlength' => true])->label('Course Code',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'course_name')->textInput(['maxlength' => true])->label('Course Name',['class'=>'mb-2 fw-bold']) ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
                $academicLevels = OrgAcademicLevels::find()->select(['academic_level_id','academic_level_name'])->orderBy([
                    'academic_level' => SORT_ASC,
                  ])->all();
                $data = ArrayHelper::map($academicLevels, 'academic_level_id', 'academic_level_name');
                echo $form
                    ->field($model, 'level_of_study')
                    ->label('Level of Study', ['class'=>'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select level of study ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'semester')->textInput()->label('Semester',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'academic_hours')->textInput()->label('Academic Hours',['class'=>'mb-2 fw-bold']) ?>

<!--   <?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>-->
   <?= $form->field($model, 'status')->label('Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
    'data' =>['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
    'language' => 'en',
    'options' => ['placeholder' => 'Select a status...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);?>
<!--    <?//= $form->field($model, 'org_unit_id')->textInput() ?>-->

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
            echo $form
                ->field($model, 'org_unit_id')
                ->label('Organisation Unit', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Organisation Unit Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'billing_factor')->hiddenInput(['value'=>'1'])->label(false); ?>


<!--    <?//= $form->field($model, 'category_id')->textInput() ?>-->


    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = CrCourseCategory::find()->select(['category_id', 'category_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'category_id', 'category_name');
            echo $form
                ->field($model, 'category_id')
                ->label('Course Category', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Course Category...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
