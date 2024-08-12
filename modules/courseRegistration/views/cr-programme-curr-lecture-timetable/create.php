<?php

use yii\helpers\Html;
use app\models\CrProgCurrTimetable;
use yii\db\ActiveQuery;
use app\models\OrgSemesterCode;

/** @var yii\web\View $this */
/** @var app\models\CrProgrammeCurrLectureTimetable $model */

$params = Yii::$app->request->get();
$session = Yii::$app->session;
$this->title = 'Create Lecture Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Lecture Timetables', 'url' => ['/courseRegistration/cr-prog-curr-timetable', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cr-programme-curr-lecture-timetable-create">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?php echo $this->render('info_card', ['params' => $params]) ?>


            <hr>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>