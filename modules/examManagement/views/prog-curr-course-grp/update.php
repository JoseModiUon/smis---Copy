<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrCourseGroup $model */

$this->title = 'Update Programme Curriculum Course Group';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Course Groups', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->course_group_id, 'url' => ['view', 'course_group_id' => $model->course_group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prog-curr-course-group-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
