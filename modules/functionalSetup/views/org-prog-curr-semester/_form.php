<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgAcademicSessionSemester;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrSemester */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-semester-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
                $progs = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'prog_curriculum_desc'])->asArray()->all();
                $data = ArrayHelper::map($progs, 'prog_curriculum_id', 'prog_curriculum_desc');
                echo $form
                    ->field($model, 'prog_curriculum_id')
                    ->label('Programme Curriculum', ['class'=>'mb-2 fw-bold'])
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

                $acadSessions = OrgAcademicSessionSemester::find()->select(['acad_session_semester_id', 'acad_session_semester_desc'])->asArray()->all();
                $data = ArrayHelper::map($acadSessions, 'acad_session_semester_id', 'acad_session_semester_desc');
                echo $form
                    ->field($model, 'acad_session_semester_id')
                    ->label('Academic Session Semester ', ['class'=>'mb-2 fw-bold'])
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
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
