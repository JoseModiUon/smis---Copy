<?php

use app\models\CrProgCurrTimetable;
use app\models\CrClassGroups;
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



    <div>
        <?= $form
            ->field($model, 'timetable_id')
            ->label(false)
            ->hiddenInput(['value' => $timetable_id]);
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
        <div class="col-md-6">
            <?= $form
                ->field($model, 'start_time')
                ->label('Start Time', ['class' => 'mb-2 fw-bold'])
                ->textInput(['type' => 'time'])
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <?= $form
                ->field($model, 'end_time')
                ->label('End Time', ['class' => 'mb-2 fw-bold'])
                ->textInput(['type' => 'time'])

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
                ->field($model, 'class_code')
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




</div>