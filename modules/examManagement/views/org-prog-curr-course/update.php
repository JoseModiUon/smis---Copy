<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\OrgProgCurrCourse $model */

$this->title = 'Update Org Prog Curr Course: ' . $model->prog_curriculum_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Prog Curr Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prog_curriculum_course_id, 'url' => ['view', 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-course-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
