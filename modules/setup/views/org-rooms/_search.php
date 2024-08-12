<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\OrgRoomsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-rooms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'room_id') ?>

    <?= $form->field($model, 'room_code') ?>

    <?= $form->field($model, 'room_name') ?>

    <?= $form->field($model, 'fk_building_id') ?>

    <?= $form->field($model, 'fk_floor_id') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'room_capacity')?>

    <?php // echo $form->field($model, 'fk_room_type')?>

    <?php // echo $form->field($model, 'fk_room_status_id')?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php // echo $form->field($model, 'room_capacity') ?>

    <?php // echo $form->field($model, 'fk_room_type') ?>

    <?php // echo $form->field($model, 'fk_room_status_id') ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
