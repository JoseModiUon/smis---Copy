<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\CrProgCurrTimetable $model */

$this->title = 'Update Cr Prog Curr Timetable: ' . $model->timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Cr Prog Curr Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->timetable_id, 'url' => ['view', 'timetable_id' => $model->timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cr-prog-curr-timetable-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
