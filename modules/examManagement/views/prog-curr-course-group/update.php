<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCourses */

$this->title = 'Update Course Prerequisite';// . $model->course_name;
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Course Prerequisites', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-course-prerequisite-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

    <?= $this->render('create', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
