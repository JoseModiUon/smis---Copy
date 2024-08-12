<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CrProgrammeCurrLectureTimetable $model */

$this->title = $model->lecture_timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Cr Programme Curr Lecture Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cr-programme-curr-lecture-timetable-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'lecture_timetable_id' => $model->lecture_timetable_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'lecture_timetable_id' => $model->lecture_timetable_id], [
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
            'lecture_timetable_id:datetime',
            'timetable_id:datetime',
            'lecture_room_id',
            'day_id',
            'start_time',
            'end_time',
            'class_code',
        ],
    ]) ?>

</div>
