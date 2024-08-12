<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmStudentSponsor $model */

$this->title = $model->sponsor_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Student Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-student-sponsor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'sponsor_id' => $model->sponsor_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sponsor_id' => $model->sponsor_id], [
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
            'sponsor_id',
            'sponsor_code',
            'sponsor_name',
            'status',
        ],
    ]) ?>

</div>
