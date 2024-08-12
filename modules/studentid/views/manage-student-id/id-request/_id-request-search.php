<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\studentid\models\search\StudentIdRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="form-student-id-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>




    <div class="row">
        <div class="col">
            <?= $form->field($model, 'reg_no')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('reg_no')
            ]) ?>

        </div>
    </div>


    <div class="row">
        <div class="col">

            <?= $form->field($model, 'student_category_id')->widget(Select2::class, [
                'data' => \app\modules\studentid\models\StudentCategory::studentCategory(),
                'options' => ['placeholder' => 'Choose academic level'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>

        <div class="col">
            <?= $form->field($model, 'prog_curriculum_id')->widget(Select2::class, [
                'data' => \app\modules\studentid\models\ProgrammeCurriculum::getProgrammes(),
                'options' => ['placeholder' => 'Choose programme curriculum'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
    </div>


    <div class="row mt-4">
        <div class="col">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>