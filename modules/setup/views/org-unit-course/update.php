<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitCourse */

$this->title = 'Update Unit Course';
$this->params['breadcrumbs'][] = ['label' => 'Unit Courses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->org_unit_course_id, 'url' => ['view', 'org_unit_course_id' => $model->org_unit_course_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-unit-course-update">
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
