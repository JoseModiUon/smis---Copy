<?php

use app\models\CrProgCurrTimetable;
use yii\helpers\Html;
use app\models\OrgRooms;
use app\models\OrgDays;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\db\ActiveQuery;

/** @var yii\web\View $this */
/** @var app\models\CrProgrammeCurrLectureTimetable $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-programme-curr-lecture-timetable-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            /**
            $types = CrProgCurrTimetable::find()
                ->select(['timetable_id', 'prog_curriculum_desc'])
                ->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $r) {
                    $r->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
                        $q->joinWith(['progCurriculum', 'orgSemesterType']);
                    }]);
                }])
                ->asArray()->all();
            $data = ArrayHelper::map($types, 'timetable_id', 'prog_curriculum_desc');
            echo $form
                ->field($model, 'timetable_id')
                ->label('Timetable', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Timetable ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
             **/
?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
    ->field($model, 'timetable_id')
    ->label(false)
    ->hiddenInput(['value' => Yii::$app->request->get('timetable_id')]);
?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
$exams = OrgRooms::find()
    ->select([
        'room_id as lecture_room_id',
        'room_name'
    ])
    ->where('true')->asArray()->all();
$data = ArrayHelper::map($exams, 'lecture_room_id', 'room_name');
echo $form
    ->field($model, 'lecture_room_id')
    ->label('Lecture Room', ['class' => 'mb-2 fw-bold'])
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
$days = OrgDays::find()
    ->select([
        'day_id',
        'day_name'
    ])
    ->where('true')->asArray()->all();
$data = ArrayHelper::map($days, 'day_id', 'day_name');
echo $form
    ->field($model, 'day_id')
    ->label('Day', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Day ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
    ->field($model, 'start_time')
    ->label('Start Time', ['class' => 'mb-2 fw-bold'])
    ->textInput(['type' => 'time'])
?>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
    ->field($model, 'end_time')
    ->label('End Time', ['class' => 'mb-2 fw-bold'])
    ->textInput(['type' => 'time'])

?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
    ->field($model, 'class_code')
    ->label('Class Code', ['class' => 'mb-2 fw-bold'])
    ->textInput(['maxlength' => true])

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