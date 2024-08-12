<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgAcademicSession;
use app\models\OrgSemesterCode;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicSessionSemester */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-academic-session-semester-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    $types = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
                    $data = ArrayHelper::map($types, 'acad_session_id', 'acad_session_name');
                    echo $form
                        ->field($model, 'acad_session_id')
                        ->label('Academic Session', ['class'=>'mb-2 fw-bold'])
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
            <div class="col-md-12">
                <?php
                    $codes = OrgSemesterCode::find()->select(['semester_code', 'semster_name'])->asArray()->all();
                    $data = ArrayHelper::map($codes, 'semester_code', 'semster_name');
                    echo $form
                        ->field($model, 'semester_code')
                        ->label('Semester', ['class'=>'mb-2 fw-bold'])
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
            <div class="col-md-12">
                <?= 
                    $form->field($model, 'acad_session_semester_desc')
                    ->textarea(['rows' => '3']) 
                    ->label('Academic Session Semester Description',['class'=>'mb-2 fw-bold']) 
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
