<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohortSession */

$this->title = $model->cohort_session_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Cohort Sessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-cohort-session-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cohort_session_id' => $model->cohort_session_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cohort_session_id' => $model->cohort_session_id], [
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
            'cohort_session_id',
            'cohort_session_name',
            'cohort_id',
            'prog_curriculum_semester_id',
            'status',
        ],
    ]) ?>

</div>
