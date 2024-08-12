<?php

use app\models\CrProgCurrTimetable;
use app\modules\courseRegistration\models\CrProgCurrTimetable as CrProgCurrTimetablePortal;

use app\models\CrProgrammeCurrLectureTimetable;
use app\models\OrgProgCurrCourse;
use app\models\OrgSemesterCode;
use kartik\dialog\Dialog;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use Mpdf\Tag\P;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgrammeCurrLectureTimetableSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);
//var_dump($_REQUEST);
//exit;
$session = Yii::$app->session;


$progCurr = OrgProgCurrCourse::findOne($tModel->prog_curriculum_course_id);


if (!empty($requestData =  Yii::$app->request->get())) {
    if (array_key_exists('timetable_id', $requestData)) {
        $session->set('timetable_id', $requestData['timetable_id']);
    }
    if (array_key_exists('course_id', $requestData)) {
        $session->set('course_id', $progCurr->course_id);
    }
}


$this->title = 'Lecture Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Course Programme Curriculum Timetable', 'url' => [
    '/courseRegistration/cr-prog-curr-timetable/create',
    'prog_curriculum_course_id' => Yii::$app->request->get('prog_curriculum_course_id'),
    'prog_curriculum_sem_group_id' => Yii::$app->request->get('prog_curriculum_sem_group_id'),
    'CrProgCurrTimetableCreateSearchRefac' => 5,
]];
$this->params['breadcrumbs'][] = $this->title;


function publish($params)
{
    $timetable = CrProgCurrTimetable::findOne($params['timetable_id']);

    if ($timetable->publish_status == 2) {
        return true;
    }
    return false;
}
$session = Yii::$app->session;

if (Yii::$app->request->get('url')) {
    $session->set('url', Yii::$app->request->get('url'));
}
?>

<div class="cr-programme-curr-lecture-timetable-index">

    <div class="card">
        <div class="card-body">
            <div class="mb-2 mt-2">
                <div>
                    <?=
                    Html::a(
                        ' Go Back',
                        [
                            '/courseRegistration/timetables/index',
                            ...Yii::$app->request->get()
                        ],
                        ['class' => ' bi bi-arrow-counterclockwise btn btn-outline-primary']
                    );
                    ?>
                </div>
            </div>
            <div class="d-flex flex-row mb-2">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            </div>
            <?php echo $this->render('info_card', ['params' => [
                'timetable_id' => $tModel->timetable_id,
                'course_id' => $progCurr->course_id
            ]]) ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end">
                        <?php if (publish(['timetable_id' => $tModel->timetable_id, 'course_id' => $progCurr->course_id])) : ?>
                            <div style="margin-right:12px">
                                <?=
                                $session->get('url');
                                ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <?=
                            Html::a(
                                ' Schedule Lecture',
                                [
                                    '/courseRegistration/cr-prog-curr-timetable/new',
                                    'prog_curriculum_course_id' => $tModel->prog_curriculum_course_id,
                                    'prog_curriculum_sem_group_id' => $tModel->prog_curriculum_sem_group_id,
                                    'course_id' => $progCurr->course_id,
                                    'marksheetId' => $tModel->mrksheet_id
                                ],
                                ['class' => ' bi bi-calendar-plus btn btn-outline-success']
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>



            <hr>

            <?= GridView::widget([
                'id' => 'lecture-timetables-grid',
                'pjax' => true,
                'export' => false,
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
                    [
                        'attribute' => 'class',
                        'value' => function ($model) {
                            if (null == $model['classGroups']) {
                                return '';
                            }
                            return $model['classGroups']['class_description'];
                        }
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} | {remove}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap;',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model) use ($tModel, $progCurr) {
                                return  Html::a(' Update', [
                                    '/courseRegistration/cr-programme-curr-lecture-timetable/update',
                                    'timetable_id' => $tModel['timetable_id'],
                                    'prog_curriculum_course_id' => $tModel['prog_curriculum_course_id'],
                                    'prog_curriculum_sem_group_id' => $tModel['prog_curriculum_sem_group_id'],
                                    'course_id' => $progCurr->course_id,
                                    'lecture_timetable_id' => $model->lecture_timetable_id
                                ], ['class' => ' bi bi-pencil btn btn-outline-primary btn-sm']);
                            },
                            'remove' => function ($url, $model) {
                                return Html::button(
                                    ' Remove',
                                    [
                                        'title' => 'Remove',
                                        'id' => 'remove-schedule-btn',
                                        'class' => ' bi bi-trash btn btn-outline-danger btn-sm',
                                        'value' => $model->lecture_timetable_id,
                                    ]
                                );
                            }
                        ],
                        'hAlign' => 'center',
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>


<?php
$urlToDeleteSchedule = '/courseRegistration/cr-programme-curr-lecture-timetable/delete';

$timetablesJs = <<< JS
let urlToDeleteSchedule = '$urlToDeleteSchedule';

$('#lecture-timetables-grid-pjax').on('click', '#remove-schedule-btn', function(e){
    let lecture_timetable_id = e.target.value


    krajeeDialog.confirm('Delete schedule?', function (result) {
        if (result) {
            const data = {
                lecture_timetable_id: lecture_timetable_id
            }
            $.ajax({
                url: urlToDeleteSchedule,
                type: 'POST',
                data: data,
                dataType: 'json',  
                success: function(data) {
                    krajeeDialog.alert('Schedule delete success!');
                    location.reload()
                },
                error: function(err) {
                    krajeeDialog.alert('Schedule delete failed!');
                }
            })
        }
    }); 
    
});

JS;
$this->registerJs($timetablesJs, yii\web\View::POS_READY);
