<?php

use app\models\OrgAcademicLevels;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgAcademicSession;
use app\models\OrgProgLevel;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgStudyGroup;
use app\models\SmStudentCategory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view-consolidated-marksheet'],
        'method' => 'get',
    ]); ?>

    <div class="row mb-2">


        <div class="col-md-8">
            <?php
            $types = OrgProgrammeCurriculum::find()->select(['prog_curriculum_id', 'concat(prog_code, \' \',prog_short_name) as desc'])
                ->innerJoinWith('prog')->asArray()->all();
$data = ArrayHelper::map($types, 'prog_curriculum_id', 'desc');
echo $form
    ->field($model, 'prog_curriculum_id')
    ->label('Programme', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Programme ...'],
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
$types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
echo $form
    ->field($model, 'acad_session_id')
    ->label('Academic Year', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Academic Year ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

        </div>

        <div class="col-md-4">
            <?php
$levels = OrgProgLevel::find()->select(['programme_level_id', 'programme_level_name'])->asArray()->all();
$data = ArrayHelper::map($levels, 'programme_level_id', 'programme_level_name');
echo $form
    ->field($model, 'programme_level_id')
    ->label('Student Level', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Student Level ...'],
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
$types = OrgStudyGroup::find()->select(['study_group_id', 'study_group_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'study_group_id', 'study_group_name');
echo $form
    ->field($model, 'study_group_id')
    ->label('Group', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Study Group ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
        </div>

        <div class="col-md-4">
            <?php
$types = SmStudentCategory::find()->select(['std_category_id', 'std_category_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'std_category_id', 'std_category_name');
echo $form
    ->field($model, 'std_category_id')
    ->label('Module', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Student Category ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

        </div>

    </div>


    <div class="row mb-2">
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