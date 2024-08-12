<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgSemesterCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-semester-code-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'semester_code')
                ->textInput(['maxlength' => true])
                ->label('Semester Code', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'semster_name')
                ->textInput(['maxlength' => true])
                ->label('Semester Name', ['class' => 'mb-2 fw-bold'])
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