<?php

use app\models\CrProgCurrTimetable;
use app\models\CrClassGroups;
use app\models\ExMode;
use yii\helpers\Html;
use app\models\OrgRooms;
use app\models\OrgDays;
use kartik\date\DatePicker;
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
    <div>
        <?= $form
            ->field($model, 'timetable_id')
            ->label(false)
            ->hiddenInput(['value' => $model->timetable_id]);
        ?>
    </div>
    <div>
        <?= $form
            ->field($lec_model, 'timetable_id')
            ->label(false)
            ->hiddenInput(['value' => $model->timetable_id]);
        ?>
    </div>
    <div>
        <?=
        $form
            ->field($model, 'prog_curriculum_course_id')
            ->hiddenInput(['value' => $params['prog_curriculum_course_id']])
            ->label(false);
        ?>
    </div>
    <div>
        <?= $form
            ->field($lec_model, 'lecture_timetable_id')
            ->label(false)
            ->hiddenInput(['value' => $params['lecture_timetable_id']]);
        ?>
    </div>




    <div class="row mb-2">
        <div class="col-md-6">
            <?php
            $days = OrgDays::find()
                ->select([
                    'day_id',
                    'day_name'
                ])
                ->where('true')->asArray()->all();
            $data = ArrayHelper::map($days, 'day_id', 'day_name');
            echo $form
                ->field($lec_model, 'day_id')
                ->label('Lecture Day', ['class' => 'mb-2 fw-bold'])
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
        <div class="col-md-6">
            <?=
            $form
                ->field($model, 'exam_date')
                ->label('Exam Date', ['class' => 'mb-2 fw-bold'])
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
       <div class="col-md-6">
            <?= $form
                ->field($lec_model, 'start_time')
                ->label('Lecture Start Time', ['class' => 'mb-2 fw-bold'])
                ->textInput(['type' => 'time'])
            ?>
        </div>  
        <div class="col-md-6">
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


    <div class="row mt-2 mb-2">
       
		  <div class="col-md-6">
            <?= $form
                ->field($lec_model, 'end_time')
                ->label('Lecture End Time', ['class' => 'mb-2 fw-bold'])
                ->textInput(['type' => 'time'])

            ?>

        </div>

        <div class="col-md-6">
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
        <div class="col-md-6">
            <?php
            $exams = OrgRooms::find()
                ->select([
                    'room_id as lecture_room_id',
                    'room_name'
                ])
                ->where('true')->asArray()->all();
            $data = ArrayHelper::map($exams, 'lecture_room_id', 'room_name');
            echo $form
                ->field($lec_model, 'lecture_room_id')
                ->label('Lecture Room', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Lecture Room ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $days = CrClassGroups::find()
                ->select([
                    'class_code',
                    'class_description'
                ])
                ->where('true')->asArray()->all();
            $data = ArrayHelper::map($days, 'class_code', 'class_description');
            echo $form
                ->field($lec_model, 'class_code')
                ->label('Class', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Class ...'],
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
                <div class="d-flex justify-content-end">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>