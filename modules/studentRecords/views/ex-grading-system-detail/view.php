<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystemDetail $model */

$this->title = $model->grading_system_detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Ex Grading System Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ex-grading-system-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'grading_system_detail_id' => $model->grading_system_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'grading_system_detail_id' => $model->grading_system_detail_id], [
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
            'grading_system_detail_id',
            'grading_system_id',
            'lower_bound',
            'upper_bound',
            'grade',
            'grade_point',
            'is_active',
            'legend',
            'extlegend',
            'recomm_id',
            'userid',
            'ip_address',
            'user_action',
            'last_update',
        ],
    ]) ?>

</div>
