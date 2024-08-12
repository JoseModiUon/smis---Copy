<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupReqCourse $model */

$this->title = $model->prog_curr_group_req_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Group Req Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prog-curr-group-req-course-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id], [
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
            'prog_curr_group_req_course_id',
            'prog_curr_group_requirement_id',
            'prog_curriculum_course_id',
            'credit_factor',
        ],
    ]) ?>

</div>
