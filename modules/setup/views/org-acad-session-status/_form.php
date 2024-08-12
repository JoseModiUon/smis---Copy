<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcadSessionStatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-acad-session-status-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'acad_session_status_name')
                ->textInput(['maxlength' => true])
                ->label('Name', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'session_status')
                ->label('Status', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select a status...'],
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