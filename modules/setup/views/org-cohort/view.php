<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohort */

$this->title = $model->cohort_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Cohorts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-cohort-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cohort_id' => $model->cohort_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cohort_id' => $model->cohort_id], [
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
            'cohort_id',
            'cohort_desc',
        ],
    ]) ?>

</div>
