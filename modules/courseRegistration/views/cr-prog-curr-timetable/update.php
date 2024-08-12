<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */

$this->title = 'Update Program Curriculum Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Program Curriculum Timetable', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->timetable_id, 'url' => ['view', 'timetable_id' => $model->timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cr-prog-curr-timetable-update">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form_update', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>