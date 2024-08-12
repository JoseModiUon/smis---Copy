<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupRequirement $model */
/** @var yii\widgets\ActiveForm $form */
?>
<style>

    .tool-tip{
        color: blue;
        font-style: italic;
        padding-top:30px;

    }
    .hint{
        color: darkred;
        float: right;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <?php echo' <div class="col">';
// echo Html::a('<i class="fa-solid fa-rotate-left"></i> Return to Programme curriculum list', ['class' => 'submit btn btn-success btn-lg', 'style'=>'float:right']) ;
echo Html::a(
    '<i class="fa-solid fa-rotate-left"></i> Return to Programme Curriculum Requirements List',
    ['/exam-management/programmes/programme-requirements',
           'prog_code' => $_REQUEST['prog_code'], 'prog_curriculum_id' => $_REQUEST['prog_curriculum_id']
        ],
    ['class' => 'btn btn-outline-success btn-sm','style' => 'float:right']
);
echo '</div><p/> <hr>';
?>
        <div class="card card-primary card-outline">
            <div class="card-body">
<div class="prog-curr-group-requirement-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'prog_curr_level_req_id')->hiddenInput(['value' => $_REQUEST['prog_curr_level_req_id']])->label(false)?>
    <?= $form->field($model, 'prog_curriculum_id')->hiddenInput(['value' => $_REQUEST['prog_curriculum_id']])->label(false)?>
    <?= $form->field($model, 'prog_code')->hiddenInput(['value' => $_REQUEST['prog_code']])->label(false)?>
    <div class="form-group row">

        <div class="col-sm-9">

            <?php
    $req_options = array("All" => "All", "Best" => "Best");

$progCurrCoursegrp = \app\models\ProgCurrCourseGroup::find()
    ->select(['course_group_id', 'course_group_name'])->asArray()->orderBy([
        'course_group_name' => SORT_ASC
    ])->all();
$data =  ArrayHelper::map($progCurrCoursegrp, 'course_group_id', 'course_group_name');
echo $form
    ->field($model, 'prog_curr_course_group_id')
    ->label('Course Group')
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Programme Curriculum Course Group ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
        </div>
    </div>

    <div class="form-group row frm-row">

    <div class="col-sm-9">

    <?= $form->field($model, 'min_group_courses')->textInput(['type' => 'number'])->label('Minimum No. of Courses') ?>
    </div>
        <div class="col-3 tool-tip">


        </div>
    </div>


    <div class="form-group row frm-row">

        <div class="col-sm-9">

            <?php
$req_options = array("All" => "All", "Best" => "Best");
echo $form
    ->field($model, 'group_pass_type')
    ->label('Group Pass Type')
    ->widget(Select2::classname(), [
        'data' => $req_options,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Group Pass Type ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

        </div>
    </div>


    <div class="form-group row frm-row">
    <div class="col-sm-9">

    <?= $form->field($model, 'min_group_pass')->textInput(['type' => 'number'])->label('Minimum No. of Courses to Pass') ?>
    </div>
    </div>
    <div class="form-group row frm-row">

    <div class="col-sm-9">
        <label for="staticEmail" class="form-label">GPA Pass Type</label>



        <?php

        echo $form
->field($model, 'gpa_pass_type')
->label(false)
->widget(Select2::classname(), [
    'data' => $req_options,
    'language' => 'en',
    'options' => ['placeholder' => 'Select GPA Pass Type ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
?> <i class="hint"> <strong>ALL</strong> Means all courses will be in Averaging (GPA) </i>

    </div>

    </div>



																								   

    <div class="form-group row frm-row">
        <div class="col-sm-9">
            <label for="staticEmail" class=" col-form-label">GPA Courses</label>

    <?= $form->field($model, 'gpa_courses')->textInput(['type' => 'number'])->label(false) ?>
            <i class="hint"> Applies ONLY if GPA Pass Type Option is Best </i>
        </div>
        <div class="col-3 tool-tip">
            Max. No. of Courses to be used in Averaging

        </div>
    </div>
    <div class="form-group row frm-row">

                <div class="col-sm-9">

                    <?php
            $req_options = array("ALL" => "Process All Courses", "IGNORE" => "Ignore / Carry Forward");

echo $form
    ->field($model, 'extra_courses_processing')
    ->label('Extra Courses')
    ->widget(Select2::classname(), [
        'data' => $req_options,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Option ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
                    <i class="hint"> Applies to <strong>Optional</strong> Courses Mostly </i>
                </div>   <div class="col-3 tool-tip">
                        How to treat Extra Courses Done

                    </div>

                </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
</div>
