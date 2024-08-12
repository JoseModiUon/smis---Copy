<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystem $model */

$this->title = $model->grading_system_id;
$this->params['breadcrumbs'][] = ['label' => 'Ex Grading Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ex-grading-system-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'grading_system_id' => $model->grading_system_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'grading_system_id' => $model->grading_system_id], [
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
            'grading_system_id',
            'grading_system_name',
            'grading_system_desc',
            'status',
        ],
    ]) ?>

</div>
