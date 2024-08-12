<?php

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CrProgrammeCurrLectureTimetable $model */
$session = Yii::$app->session;
$types = CrProgrammeCurrLectureTimetable::find()
    ->select(['prog_curriculum_desc'])
    ->joinWith(['timetable' => function (ActiveQuery $t) {
        $t->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $r) {
            $r->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
                $q->joinWith(['progCurriculum', 'orgSemesterType']);
            }]);
        }]);
    }])
    ->where(['lecture_timetable_id' => Yii::$app->request->get('lecture_timetable_id')])
    ->asArray()->one();

$this->title = 'Update Lecture Timetable: ' . $types['prog_curriculum_desc'];
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Lecture Timetables', 'url' => ['/courseRegistration/cr-programme-curr-lecture-timetable', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]];
$this->params['breadcrumbs'][] = ['label' => $types['prog_curriculum_desc'], 'url' => ['view', 'timetable_id' => $model->timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cr-programme-curr-lecture-timetable-update">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>