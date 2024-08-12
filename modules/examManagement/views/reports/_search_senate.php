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
        'action' => ['senate-reports'],
        'method' => 'get',
    ]); ?>


    <div class="row mb-2">
        <div class="col-md-4">
            <?php
            $types = OrgProgrammeCurriculum::find()
                ->select(['prog_curriculum_id', 'concat(prog_code,\' - \',prog_curriculum_desc) AS desc'])
                ->joinWith('prog')
                ->asArray()->all();
            $data = ArrayHelper::map($types, 'prog_curriculum_id', 'desc');
            echo $form
                ->field($model, 'prog_curriculum_id')
                ->label('Degree Programme', ['class' => 'mb-2 fw-bold'])
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

        <div class="col-md-4">
            <?php
            $types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
            echo $form
                ->field($model, 'acad_session_id')
                ->label('Academic Year', ['class' => 'mb-2 fw-bold'])
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

        <div class="col-md-4">
            <?php
            $data = [
                'PASS LIST' => 'pass',
                'DISCONTINUATION LIST' => 'discontinued',
                'SUPPLEMENTARY/FAIL LIST' => 'supplementary',
                'REPEAT LIST' => 'repeat',
                'INVESTIGATION LIST' => 'repeat',
            ];

            echo $form
                ->field($model, 'types')
                ->label('Types', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => array_flip($data),
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

        <div class="col-md-4">
            <?php
            $levels = OrgAcademicLevels::find()->select(['academic_level', 'academic_level_name'])->orderBy([
                'academic_level_order' => SORT_ASC
            ])->asArray()->all();
            $data = ArrayHelper::map($levels, 'academic_level', 'academic_level_name');
            echo $form
                ->field($model, 'academic_level')
                ->label('Year of Study', ['class' => 'mb-2 fw-bold'])
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
            <div style="margin-top: 33px;">
                <div class="d-flex justify-content-between">
                    <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
                </div>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>