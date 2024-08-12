<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCourses */

$this->title = 'Update Courses: ' . $model->course_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->course_id, 'url' => ['view', 'course_id' => $model->course_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-courses-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
