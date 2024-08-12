<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalType $model */

$this->title = $model->withdrawal_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-withdrawal-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'withdrawal_type_id' => $model->withdrawal_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'withdrawal_type_id' => $model->withdrawal_type_id], [
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
            'withdrawal_type_id',
            'withdrawal_type_name',
        ],
    ]) ?>

</div>
