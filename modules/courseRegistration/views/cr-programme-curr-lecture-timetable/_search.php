<?php

use yii\helpers\Html;
use yii\db\ActiveQuery;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgCurrCourse;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgAcademicSession;

use app\models\OrgProgCurrSemesterGroup;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgCurrTimetableSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-prog-curr-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="form-group">
            <h6 class="card-title text-danger">Select Programme Curriculum and click on Search</h6>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
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
                    'options' => ['placeholder' => 'Select Programme Curriculum ...'],
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
            $types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
            echo $form
                ->field($model, 'acad_session_id')
                ->label('Academic Session', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Academic Session ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>

        </div>
    </div>
    <div class="row mb-2">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <?= Html::a('  Reset Page', ['/courseRegistration/org-prog-curr-course/index'], ['class' => 'bi bi-arrow-clockwise btn btn-outline-secondary']); ?>
                <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>