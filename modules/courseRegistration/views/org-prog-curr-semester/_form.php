<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgAcademicSessionSemester;
use app\models\OrgSemesterCode;
use app\models\OrgSemesterType;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrSemester */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-semester-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $progs = OrgProgrammeCurriculum::find()->select([
                'prog_curriculum_id',                 'concat(prog_code,\' - \',prog_curriculum_desc) AS prog_desc'
            ])->innerJoinWith(['prog'])
                ->asArray()->all();
            $data = ArrayHelper::map($progs, 'prog_curriculum_id', 'prog_desc');
            echo $form
                ->field($model, 'prog_curriculum_id')
                ->label('Programme Curriculum', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Curriculum...'],
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
            $acadSessions = OrgAcademicSessionSemester::find()->innerJoinWith(['acadSession'])->asArray()->all();

            $semesters = [];

            foreach ($acadSessions as $sess) {
                $str = 'SEMESTER ' . $sess['semester_code'] . ' ' . $sess['acadSession']['acad_session_name'];

                if (!empty($semesters)) {
                    foreach ($semesters as $key => $sem) {
                        if ($sem['semester'] == $str) continue 2;
                        
                    }
                }
                $semesters[] = [
                    'acad_session_semester_id' => $sess['acad_session_semester_id'],
                    'semester' => $str
                ];
            }


            $data = ArrayHelper::map($semesters, 'acad_session_semester_id', 'semester');

            echo $form
                ->field($model, 'acad_session_semester_id')
                ->label('Academic Session Semester ', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Academic Session Semester...'],
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
            $acadSessions = OrgSemesterType::find()->select(['semester_type_id', 'semester_type'])->asArray()->all();
            $data = ArrayHelper::map($acadSessions, 'semester_type_id', 'semester_type');
            echo $form
                ->field($model, 'semester_type_id')
                ->label('Semester Type ', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Semester Type..'],
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