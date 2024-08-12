<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrCourseCategory $model */

$this->title = 'Update Course Category: ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => ' Course Categories', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->category_name, 'url' => ['']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cr-course-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
