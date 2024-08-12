<?php

use app\models\OrgAcademicSessionSemester;
use app\modules\feesManagement\models\StudentProgrammeCurriculum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\studentRecords\models\StudentSemesterSessionStatus;
use app\modules\studentRegistration\models\ProgCurrSemesterGroup;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\studentRecords\models\AcademicSession;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\StudentSemSessionProgress $model */
/** @var yii\widgets\ActiveForm $form */


$request = Yii::$app->request;

$get = $request->get();
// equivalent to: $get = $_GET;

$academic_progress_id = $request->get('academic_progress_id');
$registrationNumber = $request->get('registration_number');
$today = date('Y-m-d');
?>

<div class="student-sem-session-progress-form">
    <div class="card">
        <div class="card-body">
            <?php
            // echo $model->academicProgress->studentProgrammeCurriculum->registration_number;
            ?>

            <?php $form = ActiveForm::begin(); ?>


            <?= $form->field($model, 'registration_date')->hiddenInput(['value' => $today])->label(false) ?>



            <?= $form->field($model, 'academic_progress_id')->hiddenInput(['value' => $academic_progress_id])->label(false) ?>


            <?php
            $sp = AcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'acad_session_id', 'acad_session_name');
            echo $form
                ->field($model->academicProgress, 'acad_session_id')
                ->label('Academic Session', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Academic Session ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);

            ?>

            <?php
            $p = StudentSemesterSessionStatus::find()->select(['status_id', 'status_name'])->asArray()->all();
            $dat = ArrayHelper::map($p, 'status_id', 'status_name');
            echo $form
                ->field($model, 'rep_status_id')
                ->label('Status Name', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $dat,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Status Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>

            <?= $form->field($model, 'prom_status_id')->hiddenInput(['value' => 1])->label(false) ?>

            <?= $form->field($model, 'reporting_snyc_status')->checkbox() ?>


            <?php
            $p = OrgAcademicSessionSemester::find()->select(['acad_session_semester_id', 'acad_session_semester_desc'])->asArray()->all();
            $dat = ArrayHelper::map($p, 'acad_session_semester_id', 'acad_session_semester_desc');
             $form
                ->field($model, 'semester_progress')
                ->label('Semester Progress', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $dat,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Semester Progress ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>


            <?php
            $p = StudentProgrammeCurriculum::find()->select(['student_prog_curriculum_id', 'student_prog_curriculum_id'])->asArray()->all();
            $dat = ArrayHelper::map($p, 'student_prog_curriculum_id', 'student_prog_curriculum_id');
            echo $form
                ->field($model, 'prog_curriculum_semester_id')
                ->label('Programme Curriculum Semester', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $dat,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Curriculum Semester ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>



            <?php $form->field($model, 'promotion_status')->textInput(['maxlength' => true]) ?>


            <?= $form->field($model, 'sem_progress_number')->textInput() ?>


            <?= $form->field($model, 'billable')->textInput() ?>


            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>


        </div>
    </div>
</div>