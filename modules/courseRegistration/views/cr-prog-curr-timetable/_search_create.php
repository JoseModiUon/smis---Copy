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
        'action' => ['create'],
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

        <div class="col-md-4">
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
        <div class="col-md-4">
            <?php
$types = OrgStudyCentre::find()->select(['study_centre_id', 'study_centre_name'])->asArray()->all();
$data = ArrayHelper::map($types, 'study_centre_id', 'study_centre_name');
echo $form
    ->field($model, 'study_centre_id')
    ->label('Study Centre', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Study Centre ...'],
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
    ->label('Study Group', ['class' => 'mb-2 fw-bold'])
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
$types = OrgSemesterType::find()->select(['semester_type_id', 'semester_type'])->asArray()->all();
$data = ArrayHelper::map($types, 'semester_type_id', 'semester_type');
echo $form
    ->field($model, 'semester_type_id')
    ->label('Semester Type', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Semester Type ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

        </div>
        <div class="col-md-4">
            <?php
$types = OrgSemesterCode::find()->select(['semester_code', 'semster_name'])->orderBy([
    'semester_code' => SORT_ASC
])->asArray()->all();
$data = ArrayHelper::map($types, 'semester_code', 'semster_name');
echo $form
    ->field($model, 'semester_code')
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
    </div>


    <div class="row mb-2">




        <div class="col-md-4">
            <?php
$levels = OrgProgLevel::find()->select(['programme_level_id', 'programme_level_name'])->asArray()->all();
$data = ArrayHelper::map($levels, 'programme_level_id', 'programme_level_name');
echo $form
    ->field($model, 'programme_level_id')
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

        <div class="col-md-4">
            <div style="margin-top: 25px;">
                <div class="d-flex justify-content-between">
                    <?= Html::submitButton('  Search', ['class' => 'bi bi-search btn btn-success']) ?>
                </div>
            </div>

        </div>
        <?php if (!empty(Yii::$app->request->get('CrProgCurrTimetableCreateSearchRefac'))) : ?>
            <div class="col-md-4">
                <div style="margin-top: 25px;">
                    <div class="d-flex justify-content-between">

                        <?=
            Html::a(' View/Publish Timetables', ['/courseRegistration/cr-programme-curr-lecture-timetable', 'params' => $params], ['class' => ' bi bi-eye btn btn-primary']);
            ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>