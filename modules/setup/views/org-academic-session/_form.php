<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicSession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-academic-session-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'acad_session_name')
                ->textInput(['maxlength' => true])
                ->label('Name', ['class' => 'mb-2 fw-bold'])
            ?>


        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'acad_session_desc')
                ->textInput(['maxlength' => true])
                ->label('Description', ['class' => 'mb-2 fw-bold'])
            ?>


        </div>
    </div>

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

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>