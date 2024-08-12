<?php


use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\examProcessing\models\ExamParameters */
/* @var $form kartik\form\ActiveForm */

$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

?>
<div class="row justify-content-center">
    <div class="col-md-6">

        <?= $form->errorSummary($model); ?>

        <div class="row">
            <div class="col-md">
                <?= $form->field($model, 'programme_id')->widget(Select2::class, [
                    'data' => $model->fetchAcademicProgrammes(),
                    'options' => [
                        'placeholder' => 'Select ' . $model->getAttributeLabel('programme_id'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, 'academic_year')->widget(Select2::class, [
                    'data' => $model->fetchAcademicYear(),
                    'options' => [
                        'placeholder' => 'Select ' . $model->getAttributeLabel('academic_year'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'academic_level')->widget(Select2::class, [
                    'data' => $model->fetchAcademicLevels(),
                    'options' => [
                        'placeholder' => 'Select ' . $model->getAttributeLabel('academic_level'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md">
                <?= $form->field($model, 'student_group')->widget(Select2::class, [
                    'data' => $model->fetchStudentGroups(),
                    'options' => [
                        'placeholder' => 'Select ' . $model->getAttributeLabel('student_group'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, 'min_total_courses')->textInput() ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, 'max_total_courses')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md">
                <?= $form->field($model, 'start_reg_no')->textInput() ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, 'end_reg_no')->textInput() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Process recommendation', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>