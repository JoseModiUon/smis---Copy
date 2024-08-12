<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrCourse */

$this->title = $model->prog_curriculum_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-prog-curr-course-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id], [
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
            'prog_curriculum_course_id',
            'prog_curriculum_id',
            'course_id',
            'credit_factor',
            'credit_hours',
            'level_of_study',
            'semester',
            'prerequisite',
            'status',
            'has_course_work:boolean',
        ],
    ]) ?>

</div>
