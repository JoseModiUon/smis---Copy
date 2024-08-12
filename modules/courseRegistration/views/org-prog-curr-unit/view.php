<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrUnit */

$this->title = $model->prog_curriculum_unit_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-prog-curr-unit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id], [
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
            'prog_curriculum_unit_id',
            'org_unit_history_id',
            'prog_curriculum_id',
            'start_date',
            'end_date',
            'status',
        ],
    ]) ?>

</div>
