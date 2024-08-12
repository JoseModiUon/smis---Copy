<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmApprover $model */

$this->title = $model->approver_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Approvers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-approver-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'approver_id' => $model->approver_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'approver_id' => $model->approver_id], [
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
            'approver_id',
            'approver',
            'level',
            'status',
        ],
    ]) ?>

</div>
