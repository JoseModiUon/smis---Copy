<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomStatus $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-room-status-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'room_status_desc')->textInput()->label('Room Status', ['class' => 'mb-2 fw-bold']) ?>
    <br />
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>