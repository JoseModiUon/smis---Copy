<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrLevelRequirement $model */
/** @var yii\widgets\ActiveForm $form */

?>
<style>
    form label {
        font-weight: bold;
    }

    ul.select2-results {
        max-height: 200px;
    }
    .tool-tip{
        color: blue;
        font-style: italic;
        padding-top:5px;
    }
    .hint{
        color: darkred;
    }
    .frm-row
    {
        /*border-bottom: darkgray  1px solid;*/
        padding: 10px;
        background: ghostwhite;

    }
    .formx{
        background: white;
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
echo '</div><p/> <hr>';?>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="prog-curr-level-requirement-form">

                    <?php $form = ActiveForm::begin(); ?>



                                <div class="form-group row frm-row">
                                    <div class="col-3">
                                    <label for="staticEmail" class="col-sm-12 col-form-label">Study Level</label>
                                    </div>
                                    <div class="col-sm-6">

                                <?php
                            $req_options = array("All" => "All", "Best" => "Best");

$proglevel = \app\models\OrgProgLevel::find()
    ->select(['programme_level_id', 'programme_level_name'])->asArray()->orderBy([
        'programme_level_id' => SORT_ASC
    ])->all();
$data =  ArrayHelper::map($proglevel, 'programme_level_id', 'programme_level_name');
echo $form
    ->field($model, 'prog_study_level')
    ->label(false)
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Study Level ...'],
        'pluginOptions' => [
            'allowClear' => true
    ],
]);
?>
                            </div>
                                    <div class="col-3">


                                    </div>

                        </div>

                        
                        <div class="form-group row frm-row">
                            <div class="col-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Minimum Courses Taken</label>
												  
																															   
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'min_courses_taken', ['options' => ['class' => 'formx']])->textInput(['type' => 'number'])->label(false) ?>
                            </div>
                            <div class="col-3 tool-tip">
                                 Minimum No. of Courses Taken

                            </div>
                        </div>


                    <div class="form-group row frm-row">
                        <div class="col-3">
                           <label for="staticEmail" class="col-sm-12 col-form-label">Pass Type</label>
                        </div>
                        <div class="col-sm-6">

                                <?php
    echo $form
        ->field($model, 'pass_type')
        ->label(false)
        ->widget(Select2::classname(), [
            'data' => $req_options,
            'language' => 'en',
            'options' => ['placeholder' => 'Select Pass Type ...'],
            'pluginOptions' => [
                'allowClear' => true
        ],
    ]);
?>
                            <i class="hint"><strong>ALL</strong> Means a student MUST PASS all courses they attempt </i>
                                <?= $form->field($model, 'prog_curriculum_id')->hiddenInput(['maxlength' => true, 'value' => $_REQUEST['prog_curriculum_id']])->label(false) ?>
                                <?= $form->field($model, 'prog_code')->hiddenInput(['maxlength' => true, 'value' => $_REQUEST['prog_code']])->label(false) ?>

                        </div>

                        <div class="col-3 tool-tip">
                              Pass Type

                        </div>
                        </div>


                        <div class="form-group row frm-row">
                            <div class="col-3">

                            <label for="staticEmail" class="col-sm-12 col-form-label">Minimum Courses to Pass</label>
												  
																															  
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'min_pass_courses', ['options' => ['class' => 'formx']])->textInput(['type' => 'number'])->label(false) ?>
                                <i class="hint">Applies ONLY if Pass Type is <strong>BEST</strong> </i>
                            </div>
                                <div class="col-3 tool-tip">
                                     Minimum No. of Courses to Pass

                                </div>
                        </div>

                    <div class="form-group row frm-row">
                        <div class="col-3">

                        <label for="staticEmail" class="col-sm-12 col-form-label">Choice of GPA Courses</label>
                        </div>
                        <div class="col-sm-6">
                            <?php
    echo $form
        ->field($model, 'gpa_choice')
        ->label(false)
        ->widget(Select2::classname(), [
            'data' => $req_options,
            'language' => 'en',
            'options' => ['placeholder' => 'Select GPA Choice ...'],
            'pluginOptions' => [
                'allowClear' => true
        ],
    ]);
?>
                        </div>
                        <div class="col-3 tool-tip">
                            Choice of GPA Courses Criteria

                        </div>
                    </div>

                        <div class="form-group row frm-row">
                            <div class="col-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label">No. of GPA Courses</label>
												  
																														 
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'gpa_courses', ['options' => ['class' => 'formx']])->textInput(['type' => 'number'])->label(false) ?>
                                <i class="hint">Applies ONLY if GPA Courses Option is <strong>BEST</strong> </i>
                            </div>
                            <div class="col-3 tool-tip">
                                No. of Courses to be used in GPA/ Averaging

                            </div>
                        </div>


                        <div class="form-group row frm-row">
                            <div class="col-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Final GPA Weight</label>
                        </div>

                            <div class="col-sm-6">
                                <?= $form->field($model, 'gpa_weight', ['options' => ['class' => 'formx']])->textInput(['type' => 'number'])->label(false) ?>
                                <i class="hint"> <strong>0</strong> if NOT used in the Final Award </i>
                            </div>
                    <div class="col-3 tool-tip">
                        GPA Weight for the Level to be used in the Award

                    </div>
                        </div>


                        <div class="form-group row frm-row">
                            <div class="col-sm-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Pass Result</label></div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'pass_result', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-3 tool-tip">
                                If the student passes ALL results

                            </div>
                        </div>
                        <div class="form-group row frm-row">
                            <div class="col-sm-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label"> Pass Recommendation</label>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'pass_recommendation', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                                <i class="hint"> = <strong>AWARD</strong> if FINAL year</i>
                            </div>
                            <div class="col-3 tool-tip">
                              Recommendation for Passed Result

                            </div>
                        </div>
                        <div class="form-group row frm-row">
                            <div class="col-sm-3">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Fail Type</label>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'fail_type', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-3 tool-tip">
                                Fail Type

                            </div>
                        </div>
                        
                        <div class="form-group row frm-row">
                            <div class="col-3 ">

                            <label for="staticEmail" class="col-sm-12 col-form-label">Fail Result</label>
												  
																														  
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'fail_result', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-3 tool-tip">
                                Applicable if student has failed at least one course

                            </div>
                        </div>
                            <div class="form-group row frm-row">
                                <div class="col-3 ">

                                <label for="staticEmail" class="col-sm-12 col-form-label">Fail Recommendation</label>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'fail_recommendation', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                                </div>
                                <div class="col-3 tool-tip">
                                    Recommendation for Failed Result

                                </div>
                            </div>
                            <div class="form-group row frm-row">
                                <div class="col-3">

                                <label for="staticEmail" class="col-sm-12 col-form-label">Incomplete Result</label>
													  
																																	
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'incomplete_result', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                                    <i class="hint"> Less Courses Taken </i>
                                </div>
                                <div class="col-3 tool-tip">
                                    If the Student has passed ALL Courses BUT NOT meth the reqired Units

                                </div>
                            </div>
                            <div class="form-group row frm-row">
                                <div class="col-3">
                                <label for="staticEmail" class="col-sm-12 col-form-label">Incomplete Recommendation</label>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'incomplete_recommendation', ['options' => ['class' => 'formx']])->textInput(['maxlength' => true])->label(false) ?>
                                </div>
                                <div class="col-3 tool-tip">
                                    Recommendation Incomplete Result

                                </div>
                            </div>

                            <div class="form-group row frm-row">
                                <div class="col-sm-12">

                                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'float:right']) ?>
                                </div>
                            </div>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>