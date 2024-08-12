<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrCourseRegistration $model */

$this->title = 'Update Cr Course Registration: ' . $model->student_course_reg_id;
$this->params['breadcrumbs'][] = ['label' => 'Cr Course Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student_course_reg_id, 'url' => ['view', 'student_course_reg_id' => $model->student_course_reg_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cr-course-registration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
