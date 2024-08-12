<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomFloors $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-room-floors-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'floor_name')->textInput()->label('Floor Name', ['class' => 'mb-2 fw-bold']) ?>
    <br />
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>