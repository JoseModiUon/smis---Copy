<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHead */

$this->title = $model->unit_head_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Unit Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-unit-head-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'unit_head_id' => $model->unit_head_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'unit_head_id' => $model->unit_head_id], [
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
            'unit_head_id',
            'unit_head_name',
            'status',
        ],
    ]) ?>

</div>
