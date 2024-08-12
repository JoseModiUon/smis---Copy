<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-room-type-form">

    <?php $form = ActiveForm::begin(); ?>
 <div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'room_type_name')->textInput(['maxlength' => true]) ?>
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
