<?php

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgrammeCurrLectureTimetableSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$session = Yii::$app->session;
$types = CrProgCurrTimetable::find()
    ->select(['prog_curriculum_desc'])
    ->joinWith(['progCurriculumSemGroup' => function (ActiveQuery $r) {
        $r->joinWith(['progCurriculumSemester' => function (ActiveQuery $q) {
            $q->joinWith(['progCurriculum', 'orgSemesterType']);
        }]);
    }])
    ->where(['timetable_id' => Yii::$app->request->get('timetable_id')])
    ->asArray()->one();

$this->title = 'Lecture Timetable: ' . $types['prog_curriculum_desc'];
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Lecture Timetables', 'url' => ['/courseRegistration/cr-prog-curr-timetable', 'CrProgCurrTimetableSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id'), 'CrProgCurrTimetableSearch[acad_session_id]' => $session->get('acad_session_id')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cr-programme-curr-lecture-timetable-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Schedule Time',
                    ['create', 'timetable_id' => 1],
                    ['class' => 'btn btn-lg btn-primary']
                )
?>
            </div>
            <h1><?= Html::encode($this->title) ?></h1>


            <?= GridView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'columns' => [
    ['class' => 'kartik\grid\SerialColumn'],

    [
        'attribute' => 'room',
        'value' => 'room.room_name'
    ],
    [
        'attribute' => 'day',
        'value' => 'day.day_name'
    ],
    'start_time',
    'end_time',
    //'class_code',

    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update}',
        'contentOptions' => [
            'style' => 'white-space:nowrap;',
            'class' => 'kartik-sheet-style kv-align-middle'
        ],
        'buttons' => [
            'update' => function ($url, $model) {
                return  Html::a(' Update', ['/courseRegistration/cr-programme-curr-lecture-timetable/update', 'lecture_timetable_id' => $model->lecture_timetable_id, 'timetable_id' => $model->timetable_id], ['class' => ' bi bi-pencil btn btn-outline-primary btn-sm']);
            }
        ],
        'hAlign' => 'center',
    ],
],
            ]); ?>

        </div>
    </div>

</div>