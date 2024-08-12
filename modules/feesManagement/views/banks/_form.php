<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\Banks $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banks-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <!-- <div class="col-md-6">
            <?php //$form->field($model, 'bank_code')->textInput(['maxlength' => true]) 
            ?>
        </div> -->

        <div class="col-md-6">
            <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>