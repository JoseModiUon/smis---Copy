<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CrCourseCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cr-course-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?//= $form->field($model, 'category_id')->textInput() ?>-->

    <?= $form->field($model, 'category_name')->textInput() ?>
	<p/>
    <div class="form-group">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
