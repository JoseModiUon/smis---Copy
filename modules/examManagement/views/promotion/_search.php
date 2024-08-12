<?php

use app\models\OrgAcademicLevels as ModelsOrgAcademicLevels;
use app\models\OrgAcademicSession;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgSemesterCode;
use app\models\OrgStudyGroup;
use app\models\portal_student\OrgAcademicLevels;
use app\modules\studentRegistration\models\SemesterCode;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\PromotionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-exam-result-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-6">
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

            <div class="col-md-6">
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


        <!-- This is where the code for study_group and semester dropdown is. Do it after bug is fixed! -->


        <div class="row mb-2">
            <div class="col-md-5">
                <?php
                $levels = OrgSemesterCode::find()->select(['semester_code', 'semster_name'])->orderBy([
                    'semester_code' => SORT_ASC
                ])->asArray()->all();
                $data = ArrayHelper::map($levels, 'semester_code', 'semster_name');
                echo $form
                    ->field($model, 'level_of_study')
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
            <!-- <div class="col-md-1"></div> -->

            <div class="col-md-5">
                <?php
                $levels = ModelsOrgAcademicLevels::find()->select(['academic_level_id', 'academic_level_name'])->orderBy([
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
            
            <div class="col-md-2">
                <div style="margin-top: 33px;">
                    <div class="d-flex justify-content-end">
                        <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
                    </div>
                </div>

            </div>


            <?php ActiveForm::end(); ?>

        </div>
    </div>