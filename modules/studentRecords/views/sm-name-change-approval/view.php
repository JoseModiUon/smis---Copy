<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */

$this->title = $model->name_change_approval_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Name Change Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-name-change-approval-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'name_change_approval_id' => $model->name_change_approval_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'name_change_approval_id' => $model->name_change_approval_id], [
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
            'name_change_approval_id',
            'name_change_id',
            'approval_status',
            'remarks',
            'approved_by',
            'approval_date',
        ],
    ]) ?>

</div>
