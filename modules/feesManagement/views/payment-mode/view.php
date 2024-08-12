<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PaymentMode $model */

$this->title = $model->payment_mode_id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Modes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-mode-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'payment_mode_id' => $model->payment_mode_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'payment_mode_id' => $model->payment_mode_id], [
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
            'mode_code',
            'description',
            'mode_flag',
            'payment_mode_id',
        ],
    ]) ?>

</div>
