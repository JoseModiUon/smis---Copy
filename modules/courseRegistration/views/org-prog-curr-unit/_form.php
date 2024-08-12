<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\OrgProgrammeCurriculum;
use app\models\OrgUnitHistory;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-prog-curr-unit-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row mb-2">
            <div class="col-md-12">
                <?php
                    $orgUnitHist = OrgUnitHistory::find()
                    ->select([
                        'org_unit_history_id',
                        'unit_name'
                    ])
                    ->joinWith(['orgUnit'])
                    ->where('true')->asArray()->all();
$data = ArrayHelper::map($orgUnitHist, 'org_unit_history_id', 'unit_name');
echo $form
    ->field($model, 'org_unit_history_id')
    ->label('Organisation Unit', ['class'=>'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Unit...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
]);

?>
            </div>
        </div>

<!--    <?//= $form->field($model, 'prog_curriculum_id')->textInput() ?>-->
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

<!--   <?//= $form->field($model, 'start_date')->textInput() ?> -->

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
$form
    ->field($model, 'start_date')
    ->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter start date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
?>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?=
$form
    ->field($model, 'end_date')
    ->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter end date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
?>
        </div>
    </div>
<!--   <?//= $form->field($model, 'end_date')->textInput() ?> -->
    <?= $form->field($model, 'status')->label('Status', ['class' => 'mb-2 fw-bold'])
        ->widget(Select2::classname(), [
'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
'language' => 'en',
'options' => ['placeholder' => 'Select a status...'],
'pluginOptions' => [
    'allowClear' => true
],
        ]);

?>

<!--    <?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>-->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
