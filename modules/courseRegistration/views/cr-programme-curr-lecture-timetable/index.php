<?php

use app\models\CrProgCurrTimetable;
use app\models\OrgRooms;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\dialog\Dialog;

/** @var yii\web\View $this */
/** @var app\models\search\CrProgCurrTimetableSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);

$this->title = 'Lecture Timetable';

$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Course Programme Curriculum Timetable', 'url' => ['/courseRegistration/cr-prog-curr-timetable/create']];
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

if (!empty($requestData =  Yii::$app->request->get('CrProgCurrTimetableSearch'))) {
    if (array_key_exists('prog_curriculum_id', $requestData)) {
        $session->set('prog_curriculum_id', $requestData['prog_curriculum_id']);
    }
    if (array_key_exists('acad_session_id', $requestData)) {
        $session->set('acad_session_id', $requestData['acad_session_id']);
    }
}

$publicationStatus = function (array $data) {
    if (!empty($data)) {
        $timetableIds = array_unique(array_column($data, 'timetable_id'));
        $res = array_filter($timetableIds, function ($id) {
            $model = CrProgCurrTimetable::findOne($id);
            return $model->publish_status != 1;
        });
        return empty($res) ? 'Published' : 'Publish Timetables';
    }
};



function isDisabled($data)
{
    if (!empty($data)) {
        $timetableIds = array_unique(array_column($data, 'timetable_id'));
        $res = array_filter($timetableIds, function ($id) {
            $model = CrProgCurrTimetable::findOne($id);
            return $model->publish_status != 1;
        });
        return empty($res);
    }
    return false;
}


$columns = [
    [
        'attribute' => 'day_name',
        'label' => 'Day',
        'value' => function ($model) {
            return $model['day_name'];
        },
        'group' => true
    ],
    [
        'attribute' => 'schedule',
        'label' => 'Schedule',
        'value' => function ($model) {
            $str = $model['start_time'] . " - " . $model['end_time'] . "  " . $model['course_code'] . " - " . $model['course_name'] . " - " . $model['room_name'];
            return $str;
        }
    ]
]


?>
<div class="cr-prog-curr-timetable-index">
    <div class="card">
        <div class="card-body">
            <div class="mb-2 mt-2">
                <button class="bi bi-arrow-counterclockwise btn btn-outline-primary" onclick="history.back()"> Go Back</button>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            <hr>

            <?php if (!empty($data)) : ?>
                <div class="mt-3 mb-3">
                    <?= $this->render('info_card_2', ['params' => $params]); ?>

                </div>
                <br>

            <?php endif; ?>


            <?= GridView::widget([
                'id' => 'publish-timetable-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportConfig' => [
                    GridView::CSV => ['label' => 'CSV'],
                    GridView::EXCEL => ['label' => 'Excel']
                ],
                'toolbar' => [
                    '{toggleData}',
                    '{export}',
                    [
                        'content' =>
                        Html::button(
                            "<i class='bi bi-printer'></i> {$publicationStatus($data)}",
                            [
                                'title' => $publicationStatus($data),
                                'id' => 'publish-timetable-btn',
                                'class' => 'btn btn-primary btn-spacer btn-sm',
                                'disabled' => isDisabled($data)
                            ]
                        ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],
                'columns' => $columns,

            ]); ?>

        </div>
    </div>
</div>

<?php
$urlPublishTimetable = Url::to(['/courseRegistration/cr-prog-curr-timetable/publish']);
$timetableIds = json_encode(array_unique(array_column($data, 'timetable_id')));

$timetableJs = <<< JS
let urlPublishTimetable = '$urlPublishTimetable';
let timetableIds = $timetableIds;


$('#publish-timetable-grid-pjax').on('click', '#publish-timetable-btn', function(e){
    if(timetableIds.length == 0){
        krajeeDialog.alert('No timetables to publish.');
    }else{
        krajeeDialog.confirm('Approve timetable for publishing?', function (result) {
            if (result) {
                // $('#app-is-loading-modal').modal('show');
                                
                let data = {
                    timetableIds:timetableIds
                }
                
                $.ajax({
                    url: urlPublishTimetable,
                    type: 'POST',
                    data: data,
                    dataType: 'json',  
                    success: function(data) {
                        krajeeDialog.alert(data.message);
                        location.reload();
                    },
                    error: function(err) {
                        krajeeDialog.alert(data.message);

                        // $('#app-is-loading-modal').modal('hide');
                        // krajeeDialog.alert('Courses update fail!');
                    }
                })
            }
        }); 
    }
});

JS;
$this->registerJs($timetableJs, yii\web\View::POS_READY);
