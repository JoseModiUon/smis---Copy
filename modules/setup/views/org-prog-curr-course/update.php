<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrCourse */

$this->title = 'Update Programme Curriculum Course';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Courses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->prog_curriculum_course_id, 'url' => ['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-course-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
