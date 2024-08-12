<?php

use app\models\OrgAcademicLevels;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgAcademicSession;
use app\models\OrgProgLevel;
use app\models\OrgSemesterCode;
use app\models\OrgSemesterType;
use app\models\OrgStudyCentre;
use app\models\OrgStudyGroup;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="org-prog-curr-course-search">

  <?php $form = ActiveForm::begin([
    'action' => [''],
    'method' => 'post',
    'class' => 'form-horizontal'
  ]); ?>


  <div class="row mb-2">

    <div class="col-md-4">
      <?php
      $types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
echo $form
  ->field($model, 'acad_session_id')
  ->label('Academic Session', ['class' => 'mb-2 fw-bold'])
  ->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Academic Session ...',],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]);
?>
    </div>

    <div class="col-md-4">
      <?php
$types = OrgProgrammeCurriculum::find()
  ->select(['prog_curriculum_id', 'concat(prog_code,\' - \',prog_curriculum_desc) AS desc'])
  ->joinWith('prog')
  ->asArray()->all();
$data = ArrayHelper::map($types, 'prog_curriculum_id', 'desc');
echo $form
  ->field($model, 'prog_curriculum_id')
  ->label('Programme Curriculum', ['class' => 'mb-2 fw-bold'])
  ->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Programme Curriculum ...',],

    'pluginOptions' => [
      'allowClear' => true,
      'initialize' => true,

    ],
  ]);
?>
    </div>


    <div class="col-md-4">
      <?php
$levels = OrgAcademicLevels::find()->select(['academic_level_id', 'academic_level_name'])->orderBy([
  'academic_level_order' => SORT_ASC
])->asArray()->all();
$data = ArrayHelper::map($levels, 'academic_level_id', 'academic_level_name');
echo $form
  ->field($model, 'academic_level_id')
  ->label('Programme Level', ['class' => 'mb-2 fw-bold'])
  ->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Level ...'],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]);
?>
    </div>


    <div class="col-md-4">
      <?php
$types = OrgSemesterCode::find()->select(['semester_code', 'semster_name'])->orderBy([
  'semester_code' => SORT_ASC
])->asArray()->all();
$data = ArrayHelper::map($types, 'semester_code', 'semster_name');
echo $form
  ->field($model, 'semester_code')
  ->label('Semester', ['class' => 'mb-2 fw-bold'])
  ->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Semester ...'],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]);
?>
    </div>

    <div class="col-md-4">
      <?php
$types = OrgStudyGroup::find()->select(['study_group_id', 'study_group_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'study_group_id', 'study_group_name');
echo $form
  ->field($model, 'study_group_id')
  ->label('Study Group', ['class' => 'mb-2 fw-bold'])
  ->widget(Select2::classname(), [
    'data' => $data,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Study Group ...'],
    'pluginOptions' => [
      'allowClear' => true
    ],
  ]);
?>
    </div>


    <div class="col-md-12">

      <div class="row g-12 align-items-center">
        <div class="col-auto">
          <?php $model->min_courses_taken = 12; ?>
          <?= $form->field($model, 'min_courses_taken')->textInput()->hint('')->label('From Min Total Courses Taken:') ?>

        </div>
        <div class="col-auto">
          <?php $model->max_courses_taken = 60; ?>
          <?= $form->field($model, 'max_courses_taken')->textInput()->hint('')->label('To Max Total Courses Taken:') ?>
        </div>


      </div>
    </div>
  </div>
  <div class="col-md-12 clearfix">
    <div class="row g-12 align-items-center">
      <div class="col-auto">
        <?php $model->from_reg_no = '00000000'; ?>
        <?= $form->field($model, 'from_reg_no')->textInput()->hint('')->label('From Reg No:') ?>
      </div>
      <div class="col-auto">
        <?php $model->to_reg_no = 'ZZZZZZZZ'; ?>
        <?= $form->field($model, 'to_reg_no')->textInput()->hint('')->label('To Reg No:') ?>
      </div>
    </div>
  </div>
  <div class="col-md-12 clearfix">
    <div class="row col-12 align-items-center">
      <div class="col-auto">
        <?= $form->field($model, 'trace_processing')->checkbox()->label('') ?>
      </div>

    </div>
  </div>
</div>

<div class="row mb-2">
  <div class="col-md-6">
    <div style="margin-top: 33px;">
      <div class="d-flex justify-content-between">
        <?= Html::submitButton('  Process Exam', ['class' => 'bi bi-award btn btn-success']) ?>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>

</div>
