<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */

$this->title = $model->timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Cr Prog Curr Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cr-prog-curr-timetable-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'timetable_id' => $model->timetable_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'timetable_id' => $model->timetable_id], [
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
            'timetable_id:datetime',
            'prog_curriculum_course_id',
            'prog_curriculum_sem_group_id',
            'exam_date',
            'exam_venue',
            'exam_mode',
        ],
    ]) ?>

</div>
