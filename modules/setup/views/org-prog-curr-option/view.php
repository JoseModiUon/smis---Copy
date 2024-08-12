<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOption */

$this->title = $model->option_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-prog-curr-option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'option_id' => $model->option_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'option_id' => $model->option_id], [
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
            'option_id',
            'option_code',
            'option_name',
            'degree_id',
            'option_desc',
            'option_status',
            'progress_type',
        ],
    ]) ?>

</div>
