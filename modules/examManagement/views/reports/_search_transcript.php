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
use app\models\Student;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['provisional-transcript'],
        'method' => 'get',
    ]); ?>

    <div class="row mb-2">
        <hr>
        <span class="fst-italic text-danger pb-2">Search student details with below form*</span>

        <div class="col-md-4">
            <?php
            $student = Student::find()->select(['student_id', 'concat(student_number,\' - \',concat(surname,\' \',other_names)) AS desc'])->asArray()->all();
            $data = ArrayHelper::map($student, 'student_id', 'desc');
            echo $form
                ->field($model, 'student_id')
                ->label('Student Number', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Registration Number ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>

        </div>


        <div class="col-md-4">
            <div style="margin-top: 29px;">
                <div class="d-flex justify-content-between">
                    <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
                </div>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>