<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\OrgBuilding $model */

$this->title = $model->building_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Buildings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-building-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'building_id' => $model->building_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'building_id' => $model->building_id], [
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
            'building_id',
            'building_code',
            'building_name',
            'floors',
            'study_center',
        ],
    ]) ?>

</div>
