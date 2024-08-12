<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrLevelRequirement $model */

$this->title = $model->prog_curr_level_req_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Level Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prog-curr-level-requirement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curr_level_req_id' => $model->prog_curr_level_req_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curr_level_req_id' => $model->prog_curr_level_req_id], [
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
            'prog_curr_level_req_id',
            'prog_curriculum_id',
            'prog_study_level',
            'min_courses_taken',
            'pass_type',
            'min_pass_courses',
            'gpa_choice',
            'gpa_courses',
            'gpa_weight',
            'pass_result',
            'pass_recommendation',
            'fail_type',
            'fail_result',
            'fail_recommendation',
            'incomplete_result',
            'incomplete_recommendation',
        ],
    ]) ?>

</div>
