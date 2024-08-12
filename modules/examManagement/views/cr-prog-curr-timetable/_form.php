<?php

use app\models\ExMode;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgCurrSemesterGroup;
use app\models\OrgRooms;
use yii\db\ActiveQuery;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-prog-curr-timetable-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $progCurr = OrgProgCurrCourse::find()
                ->select([
                    'prog_curriculum_course_id',
                    'prog_code',
                    'concat(prog_code,\' ~ \',prog_curriculum_desc,\' - \',course_code,\' ~ \',course_name) AS desc'
                ])
                ->joinWith(['course'])
                ->joinWith(['progCurriculum' => function (ActiveQuery $q) {
                    $q->joinWith('prog');
                }])
                ->where('true')->asArray()->all();
$data = ArrayHelper::map($progCurr, 'prog_curriculum_course_id', 'desc');

echo $form
    ->field($model, 'prog_curriculum_course_id')
    ->label('Program Curriculum Course', ['class' => 'mb-2 fw-bold'])
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
$progCurr = OrgProgCurrSemesterGroup::find()
    ->select([
        'prog_curriculum_sem_group_id',
        'concat(\'PROG LEVEL: \',programme_level_name,\' - SEM TYPE: \',semester_type,\'- ACAD SESSION: \',acad_session_semester_desc,\' - STUDY CENTER: \',study_centre_name,\' - GROUP: \',study_group_name) AS desc'

    ])
    ->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
        $q->joinWith(['progCurriculum', 'acadSessionSemester', 'orgSemesterType']);
    }])
    ->joinWith(['studyCentreGroup' => function (ActiveQuery $q) {
        $q->joinWith(['studyCentre', 'studyGroup']);
    }])
    ->joinWith(['programmeLevel'])
    ->where('true')->asArray()->all();
$data = ArrayHelper::map($progCurr, 'prog_curriculum_sem_group_id', 'desc');

echo $form
    ->field($model, 'prog_curriculum_sem_group_id')
    ->label('Program Curriculum Semester Group', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Program Curriculum Semester...'],
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
    ->field($model, 'exam_date')
    ->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter Exam Date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'

        ]
    ]);
?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
$exams = OrgRooms::find()
    ->select([
        'room_id as exam_venue',
        'room_name'
    ])
    ->where('true')->asArray()->all();
$data = ArrayHelper::map($exams, 'exam_venue', 'room_name');
echo $form
    ->field($model, 'exam_venue')
    ->label('Exam Venue', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Exam Venue ...'],
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
$exams = ExMode::find()
    ->select([
        'exam_mode_id as exam_mode',
        'exam_mode_name'
    ])
    ->where('true')->asArray()->all();
$data = ArrayHelper::map($exams, 'exam_mode', 'exam_mode_name');

echo $form
    ->field($model, 'exam_mode')
    ->label('Exam Mode', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Exam Mode ...'],
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