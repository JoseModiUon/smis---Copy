<?php

use yii\helpers\Html;
use app\models\OrgCohort;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgCohortSession;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgCurrSemester;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohortSession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-cohort-session-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= 
                $form
                ->field($model, 'cohort_session_name')
                ->label('Session Name', ['class'=>'mb-2 fw-bold'])
                ->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
                $cohorts = OrgCohort::find()->select(['cohort_id', 'cohort_desc'])->asArray()->all();
                $data = ArrayHelper::map($cohorts, 'cohort_id', 'cohort_desc');
                echo $form
                    ->field($model, 'cohort_id')
                    ->label('Cohort', ['class'=>'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Cohort ...'],
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
                $progCurr = OrgProgCurrSemester::find()
                ->select([
                    'prog_curriculum_semester_id',
                    'concat(acad_session_semester_desc,\' - \',prog_curriculum_desc) AS desc'
                ])
                ->joinWith(['acadSessionSemester','progCurriculum'])
                ->where('true')->asArray()->all();
                $data = ArrayHelper::map($progCurr, 'prog_curriculum_semester_id', 'desc');

                echo $form
                    ->field($model, 'prog_curriculum_semester_id')
                    ->label('Program Curriculum Semester', ['class'=>'mb-2 fw-bold'])
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
