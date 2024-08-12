<?php

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\studentid\models\search\StudentIdSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<<<<<<< HEAD
<<<<<<< HEAD

<div class="form-student-id-search">

=======

<div class="form-student-id-search">

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

<div class="form-student-id-search">

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD

    <?= $form->field($model, 'student_id_serial_no')->textInput(['placeholder' => 'Student Id Serial No']) ?>

=======

    <?= $form->field($model, 'student_id_serial_no')->textInput(['placeholder' => 'Student Id Serial No']) ?>

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

    <?= $form->field($model, 'student_id_serial_no')->textInput(['placeholder' => 'Student Id Serial No']) ?>

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= $form->field($model, 'student_prog_curr_id')->widget(Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\SmStudentProgrammeCurriculum::find()->orderBy('student_prog_curriculum_id')->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
        'options' => ['placeholder' => 'Choose Sm student programme curriculum'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD

=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= $form->field($model, 'issuance_date')->widget(DateControl::class, [
        'type' => DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Issuance Date',
                'autoclose' => true
            ]
        ],
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD

=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= $form->field($model, 'valid_from')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Valid From',
                'autoclose' => true
            ]
        ],
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD

=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= $form->field($model, 'valid_to')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => 'Choose Valid To',
                'autoclose' => true
            ]
        ],
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD

    <?php /* echo $form->field($model, 'barcode')->textInput(['placeholder' => 'Barcode']) */ ?>

    <?php /* echo $form->field($model, 'id_status')->textInput(['maxlength' => true, 'placeholder' => 'Id Status']) */ ?>

    <?php /* echo $form->field($model, 'request_id')->textInput(['placeholder' => 'Request']) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <?php /* echo $form->field($model, 'barcode')->textInput(['placeholder' => 'Barcode']) */ ?>

    <?php /* echo $form->field($model, 'id_status')->textInput(['maxlength' => true, 'placeholder' => 'Id Status']) */ ?>

    <?php /* echo $form->field($model, 'request_id')->textInput(['placeholder' => 'Request']) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
