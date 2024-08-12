<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\OrgCourses;
use app\models\OrgUnit;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitCourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-unit-course-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    <?//= $form->field($model, 'course_id')->textInput() ?>-->
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgCourses::find()->select(['course_id', 'course_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'course_id', 'course_name');
            echo $form
                ->field($model, 'course_id')
                ->label('Course Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Course Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
<!--    <?//= $form->field($model, 'org_unit_id')->textInput() ?>-->
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
            echo $form
                ->field($model, 'org_unit_id')
                ->label('Unit Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Unit Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
<!--    <?//= $form->field($model, 'org_teaching_id')->textInput() ?>-->
    <div class="col-md-12">
        <?php
        $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
        $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
        echo $form
            ->field($model, 'org_teaching_id')
            ->label('Teaching Organization/ Unit Name', ['class'=>'mb-2 fw-bold'])
            ->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'en',
                'options' => ['placeholder' => 'Select Teaching Organization/Unit ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        ?>
    </div>
</div>
<!--    <?//= $form->field($model, 'start_date')->textInput() ?>-->

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
<!--    <?//= $form->field($model, 'end_date')->textInput() ?>-->
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
    <?= $form->field($model, 'user_id')->hiddenInput(['value' =>Yii::$app->user->id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
