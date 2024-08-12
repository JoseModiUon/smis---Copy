<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupRequirement $model */

$this->title = $model->prog_curr_group_requirement_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Group Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prog-curr-group-requirement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curr_group_requirement_id' => $model->prog_curr_group_requirement_id], [
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
            'prog_curr_group_requirement_id',
            'prog_curr_level_req_id',
            'prog_curr_course_group_id',
            'min_group_courses',
            'group_pass_type',
            'min_group_pass',
            'gpa_pass_type',
            'gpa_courses',
            'extra_courses_processing',
        ],
    ]) ?>

</div>
