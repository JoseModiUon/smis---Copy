<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrGroupReqCourse $model */

$this->title = 'Update Prog Curr Group Req Course: ' . $model->prog_curr_group_req_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Group Req Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prog_curr_group_req_course_id, 'url' => ['view', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prog-curr-group-req-course-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
