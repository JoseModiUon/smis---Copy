<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Update Course Prerequisite';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Prerequisites', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-course-prerequisite-update">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'prog_curr_course_group_id')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'min_group_courses')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'group_pass_type')->textInput() ?>
            <?= $form->field($model, 'min_group_pass')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'gpa_pass_type')->textInput() ?>
            <?= $form->field($model, 'gpa_courses')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'extra_courses_processing')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>