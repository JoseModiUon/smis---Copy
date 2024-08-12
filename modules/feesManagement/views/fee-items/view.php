<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\FeeItems $model */

$this->title = $model->fee_code;
$this->params['breadcrumbs'][] = ['label' => 'Fee Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fee-items-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'fee_code' => $model->fee_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'fee_code' => $model->fee_code], [
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
            'fee_code',
            'fee_description',
            'priority',
            'naration',
            'fee_type',
            'publish',
            'fee_code_alias',
        ],
    ]) ?>

</div>
