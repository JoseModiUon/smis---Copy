<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgKuccpsProgMap */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-kuccps-prog-map-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-">
            <?= 
                $form
                ->field($model, 'kuccps_prog_code')
                ->label('Kuccps Program Code',['class'=>'mb-2 fw-bold']) 
                ->textInput(['maxlength' => true]) 
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-">
            <?= 
                $form
                ->field($model, 'kuccps_prog_name')
                ->label('Kuccps Program Name',['class'=>'mb-2 fw-bold']) 
                ->textInput(['maxlength' => true]) 
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-">
            <?= 
                $form
                ->field($model, 'uon_prog_code')
                ->label('Uon Program Code',['class'=>'mb-2 fw-bold']) 
                ->textInput(['maxlength' => true]) 
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
