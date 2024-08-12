<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\CrProgCurrTimetable $model */

$this->title = 'Create Cr Prog Curr Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Cr Prog Curr Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cr-prog-curr-timetable-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
