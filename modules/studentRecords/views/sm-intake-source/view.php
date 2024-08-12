<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmIntakeSource $model */

$this->title = $model->source_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Intake Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-intake-source-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'source_id' => $model->source_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'source_id' => $model->source_id], [
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
            'source_id',
            'source',
        ],
    ]) ?>

</div>
