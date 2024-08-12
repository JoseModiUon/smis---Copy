<?php

use app\models\OrgAcademicSession;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmIntakes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-intakes-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'intake_name')
                ->textInput(['maxlength' => true])
                ->label('Intake Name', ['class' => 'mb-2 fw-bold'])
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $codes = OrgAcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($codes, 'acad_session_id', 'acad_session_name');
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

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>