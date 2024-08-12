<?php

use app\models\ExGradingSystem;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystemDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ex-grading-system-detail-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $grading_system = ExGradingSystem::find()->select(['grading_system_id', 'grading_system_desc'])->asArray()->all();
            $data = ArrayHelper::map($grading_system, 'grading_system_id', 'grading_system_desc');
            echo $form
                ->field($model, 'grading_system_id')
                ->label('Grading System', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Grading System ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'lower_bound')
                ->textInput()
                ->label('Lower Bound ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'upper_bound')
                ->textInput()
                ->label('Upper Bound ', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'grade')
                ->textInput()
                ->label('Grade', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'grade_point')
                ->textInput()
                ->label('Grade Point', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            echo $form
                ->field($model, 'is_active')
                ->label('Status', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Status ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>



    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>