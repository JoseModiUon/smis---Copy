<?php

use app\models\OrgAcademicLevels;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgCourses;
use app\models\OrgProgrammeCurriculum;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrCourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-course-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    <?//= $form->field($model, 'prog_curriculum_id')->textInput() ?>-->
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'prog_curriculum_desc'])->asArray()->all();
            $data = ArrayHelper::map($types, 'prog_curriculum_id', 'prog_curriculum_desc');
            echo $form
                ->field($model, 'prog_curriculum_id')
                ->label('Programme Curriculum', ['class'=>'mb-2 fw-bold'])
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

<!--   <?//= $form->field($model, 'course_id')->textInput() ?> -->

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgCourses::find()->select(['course_id', 'course_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'course_id', 'course_name');
            echo $form
                ->field($model, 'course_id')
                ->label('Course', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Course ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'credit_factor')->textInput() ?>

    <?= $form->field($model, 'credit_hours')->textInput() ?>

    <?php //$form->field($model, 'level_of_study')->textInput() ?>

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
                        'options' => ['placeholder' => 'Select Level of Study ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'semester')->textInput() ?>

<!--    <?//= $form->field($model, 'prerequisite')->textInput() ?>-->
    

<!--    <?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'status')->label('Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
    'data' =>['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
    'language' => 'en',
    'options' => ['placeholder' => 'Select a status...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);?>

    <?= $form->field($model, 'has_course_work')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
