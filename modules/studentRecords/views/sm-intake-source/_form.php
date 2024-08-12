<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmIntakeSource $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-intake-source-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?=
            $form
                ->field($model, 'source')
                ->textInput(['maxlength' => true])
                ->label('Source', ['class' => 'mb-2 fw-bold'])
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