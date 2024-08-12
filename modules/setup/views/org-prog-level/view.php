<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgLevel */

$this->title = $model->programme_level_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-prog-level-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'programme_level_id' => $model->programme_level_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'programme_level_id' => $model->programme_level_id], [
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
            'programme_level_id',
            'programme_level_name',
        ],
    ]) ?>

</div>
