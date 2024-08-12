<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CrCourseRegistration $model */

$this->title = $model->student_course_reg_id;
$this->params['breadcrumbs'][] = ['label' => 'Cr Course Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cr-course-registration-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'student_course_reg_id' => $model->student_course_reg_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'student_course_reg_id' => $model->student_course_reg_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'student_course_reg_id',
            'timetable_id:datetime',
            'student_semester_session_id',
            'course_registration_type_id',
            'registration_date',
            'course_reg_status_id',
            'source_ipaddress',
            'userid',
            'class_code',
            'registration_number',
        ],
    ]) ?>

</div>
