<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmStudentCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-student-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'std_category_name')
                ->textInput()
                ->label('Category Name', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>