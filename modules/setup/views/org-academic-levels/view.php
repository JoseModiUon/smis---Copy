<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicLevels */

$this->title = $model->academic_level_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Academic Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-academic-levels-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'academic_level_id' => $model->academic_level_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'academic_level_id' => $model->academic_level_id], [
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
            'academic_level_id',
            'academic_level',
            'academic_level_name',
            'academic_level_order',
            'status',
        ],
    ]) ?>

</div>
