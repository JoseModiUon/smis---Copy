<?php

use yii\helpers\Html;
use app\models\OrgCourses;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgCurrSemester;
/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-course-prerequisite-form">

    <?php $form = ActiveForm::begin(); ?>


        <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    $progCurr = OrgProgCurrCourse::find()
                    ->select([
                        'prog_curriculum_course_id',
                        'concat(prog_curriculum_desc,\' - \',course_name) AS desc'
                    ])
                    ->joinWith(['course','progCurriculum'])
                    ->where('true')->asArray()->all();
                    $data = ArrayHelper::map($progCurr, 'prog_curriculum_course_id', 'desc');

                    echo $form
                        ->field($model, 'prog_curriculum_course_id')
                        ->label('Program Curriculum Course', ['class'=>'mb-2 fw-bold'])
                        ->widget(Select2::classname(), [
                            'data' => $data,
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Program Curriculum ...'],
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
                    $types = OrgCourses::find()->select(['course_id', 'course_name'])->asArray()->all();
                    $data = ArrayHelper::map($types, 'course_id', 'course_name');
                    echo $form
                        ->field($model, 'course_id')
                        ->label('Course', ['class'=>'mb-2 fw-bold'])
                        ->widget(Select2::classname(), [
                            'data' => $data,
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Course  ...'],
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
