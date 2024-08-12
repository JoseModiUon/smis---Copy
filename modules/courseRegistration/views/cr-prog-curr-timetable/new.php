<?php

use app\models\CrProgrammeCurrLectureTimetable;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgCurrTimetable $model */

$this->title = 'Create Program Curriculum Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Program Curriculum Timetable', 'url' => ['create']];
// $this->params['breadcrumbs'][] = ['label' => $model->timetable_id, 'url' => ['view', 'timetable_id' => $model->timetable_id]];
$this->params['breadcrumbs'][] = 'Create';
$params = [
    'timetable_id' => $model->timetable_id,
    'course_id' => Yii::$app->request->get('course_id')
];
?>
<div class="cr-prog-curr-timetable-update">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>


            <?php echo $this->render('info_card', ['params' => $params]) ?>


            <hr>
            <?= $this->render('_form_update', [
                'model' => $model,
                'lec_model' => new CrProgrammeCurrLectureTimetable()

            ]) ?>

        </div>
    </div>
</div>