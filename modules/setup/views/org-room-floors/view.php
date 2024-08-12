<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomFloors $model */

$this->title = $model->floor_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Room Floors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-room-floors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'floor_id' => $model->floor_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'floor_id' => $model->floor_id], [
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
            'floor_id',
            'floor_name',
        ],
    ]) ?>

</div>
