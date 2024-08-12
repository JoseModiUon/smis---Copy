<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrCourseRegistration $model */

$this->title = 'Create Cr Course Registration';
$this->params['breadcrumbs'][] = ['label' => 'Cr Course Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cr-course-registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>