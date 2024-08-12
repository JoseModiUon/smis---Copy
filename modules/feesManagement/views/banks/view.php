<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\Banks $model */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="banks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'brank_id' => $model->brank_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'brank_id' => $model->brank_id], [
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
            'bank_code',
            'bank_name',
            'bank_branch',
            'account_no',
            'description',
            'currency_id',
            'brank_id',
        ],
    ]) ?>

</div>