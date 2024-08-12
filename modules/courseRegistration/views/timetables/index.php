<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 11:53 AM
 */

/**
 * @var $this yii\web\View
 * @var string $title
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $searchModel
 * @var app\modules\courseRegistration\models\filters\TimetableFilter $timetableFilter
 * @var string $filterModel
 * @var string $panelHeader
 * @var string $progCurrId
 * @var string $level
 * @var string $partialMarksheetId
 * @var string $studyCenterId
 * @var string $studyGroupId
 * @var string $panelHeader
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

?>

<!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb([
    'Course registration' => Url::to(['/courseRegistration']),
    'List timetables' => null
]);
?>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-10 offset-1">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Filter timetables
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        echo $this->render('timetable_filters', ['model' => $timetableFilter])
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-10 offset-1">
                <div class="timetables">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                </div>
            </div>
            <div class="col-10 offset-1">
                <?php
                $codeCol = [
                    'attribute' => 'cse.course_code',
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return $model['programmeCurriculumCourse']['course']['course_code'];
                    }
                ];
                $marksheetCol = [
                    'attribute' => 'mrksheet_id',
                    'label' => 'MARKSHEET ID',
                    'vAlign' => 'middle',
                ];
                $nameCol = [
                    'attribute' => 'cse.course_name',
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return $model['programmeCurriculumCourse']['course']['course_name'];
                    }
                ];
                $actionCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'ACTIONS',
                    'template' => '{schedule}{update}',
                    'contentOptions' => ['style' => 'white-space:nowrap;', 'class' => 'kartik-sheet-style kv-align-middle'],
                    'buttons' => [
                        'schedule' => function ($url, $model) {
                            return Html::a(
                                '<i class="fas fa-add"></i> Add lecture schedule',
                                Url::to([
                                    // '/courseRegistration/timetables/create-schedule',
                                    '/courseRegistration/cr-programme-curr-lecture-timetable/view',
                                    'marksheetId' => $model['mrksheet_id'],
                                    ...Yii::$app->request->get()
                                ]),
                                [
                                    'title' => 'Add or View lecture schedule',
                                    'class' => 'btn btn-link'
                                ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<i class="fas fa-edit"></i> Edit',
                                Url::to([
                                    '/courseRegistration/timetables/edit',
                                    'marksheetId' => $model['mrksheet_id']
                                ]),
                                [
                                    'title' => 'Update timetable',
                                    'class' => 'btn btn-link'
                                ]
                            );
                        }
                    ]
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $widget) {
                            return [
                                'value' => $model['timetable_id']
                            ];
                        }
                    ],
                    $marksheetCol,
                    $codeCol,
                    $nameCol,
                    $actionCol
                ];

                $toolbar = [
                    [
                        'content' =>
                        Html::a(
                            '<i class="fas fa-add"></i>  Add timetable',
                            Url::to([
                                '/courseRegistration/timetables/create',
                                'progCurrId' => $progCurrId,
                                'level' => $level,
                                'partialMarksheetId' => $partialMarksheetId,
                                'centerId' => $studyCenterId,
                                'groupId' => $studyGroupId
                            ]),
                            [
                                'title' => 'Add timetable',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]
                        ) . '&nbsp;' .
                            Html::button('<i class="fas fa-copy"></i> Publish timetables', [
                                'title' => 'Publish or Update timetables in student\'s portal',
                                'id' => 'publish-timetable-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ];

                try {
                    echo GridView::widget([
                        'id' => 'courses-in-timetable-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $filterModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => $toolbar,
                        'export' => false,
                        'panel' => [
                            'heading' => $panelHeader
                        ],
                        'persistResize' => false,
                        'itemLabelSingle' => 'course',
                        'itemLabelPlural' => 'courses',
                    ]);
                } catch (Throwable $ex) {
                    $message = $ex->getMessage();
                    if (YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
$publishTimetableUrl = Url::to(['/courseRegistration/timetables/publish']);

$listTimetableJs = <<< JS
const publishTimetableUrl = '$publishTimetableUrl';
const timetableLoader = $('.timetables > .loader');
timetableLoader.html(loader);
timetableLoader.hide();
const timetableErrorDisplay =  $('.timetables > .error-display');
timetableErrorDisplay.hide();

//Publish timetable 
$('#courses-in-timetable-grid-pjax').on('click', '#publish-timetable-btn', function (e){
    e.preventDefault();
    let timetableIds = getSelectedIds('#courses-in-timetable-grid');
    if(timetableIds.length === 0){
        alert('No timetables have been selected for publishing.');
    }else{
        if(confirm('Publish timetables to students\' portal?')){
            timetableErrorDisplay.hide();
            timetableLoader.show(); 
            $.ajax({
                url: publishTimetableUrl,
                type: 'POST',
                data: {
                    'timetableIds': timetableIds
                }
            }).done(function (data){
                timetableLoader.hide();
                if(!data.success){
                    timetableErrorDisplay.html(data.message);
                    timetableErrorDisplay.show();
                }
            }).fail(function (data){
                timetableLoader.hide();
                timetableErrorDisplay.html(data.responseText);
                timetableErrorDisplay.show();
            });
        }
    }
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
JS;
$this->registerJs($listTimetableJs, yii\web\View::POS_READY);
