<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRegistration\models\AcademicSession $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="academic-session-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'acad_session_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acad_session_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
