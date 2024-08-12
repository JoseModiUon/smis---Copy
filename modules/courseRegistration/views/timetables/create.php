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
 * @var string $panelHeader
 * @var string $progCurrId
 * @var string $level
 * @var string $partialMarksheetId
 * @var string $studyCenterId
 * @var string $studyGroupId
 * @var string $filters
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
    'List timetables' => Url::to(['/courseRegistration/timetables/index', 'TimetableFilter' => $filters]),
    'Create timetables' => null
]);
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="new-timetable">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                </div>
            </div>
            <div class="col-10 offset-1">
                <?php
                $codeCol = [
                    'attribute' => 'course_code',
                    'label' => 'CODE',
                    'vAlign' => 'middle'
                ];
                $nameCol = [
                    'attribute' => 'course_name',
                    'label' => 'NAME',
                    'vAlign' => 'middle'
                ];
                $levelCol = [
                    'attribute' => 'level_of_study',
                    'label' => 'LEVEL OF STUDY',
                    'vAlign' => 'middle',
                    'group' => true
                ];
                $semesterCol = [
                    'attribute' => 'semester',
                    'label' => 'SEMESTER',
                    'vAlign' => 'middle',
                    'group' => true
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $widget) {
                            return [
                                'value' => $model['prog_curriculum_course_id']
                            ];
                        }
                    ],
                    $semesterCol,
                    $codeCol,
                    $nameCol
                ];

                $toolbar = [
                    [
                        'content' =>
                            Html::button('<i class="fas fa-plus"></i> Add to timetable', [
                                'title' => 'Add to timetable',
                                'id' => 'create-timetable-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ];

                try {
                    echo GridView::widget([
                        'id' => 'prog-courses-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
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
$newTimetableUrl = Url::to(['/courseRegistration/timetables/store']);

$createTimetableJs = <<< JS
const newTimetableUrl = '$newTimetableUrl';
const newTimetableLoader = $('.new-timetable > .loader');
newTimetableLoader.html(loader);
newTimetableLoader.hide();
const newTimetableErrorDisplay =  $('.new-timetable > .error-display');
newTimetableErrorDisplay.hide();

//Create timetable 
$('#prog-courses-grid-pjax').on('click', '#create-timetable-btn', function (e){
    e.preventDefault();
    let progCoursesIds = getSelectedIds('#prog-courses-grid');
    if(progCoursesIds.length === 0){
        alert('No courses have been selected.');
    }else{
        if(confirm('Create timetables for selected courses?')){
            newTimetableErrorDisplay.hide();
            newTimetableLoader.show(); 
            $.ajax({
                url: newTimetableUrl,
                type: 'POST',
                data: {
                    'progCoursesIds': progCoursesIds,
                    'progCurrId': '$progCurrId',
                    'level': '$level',
                    'partialMarksheetId': '$partialMarksheetId',
                    'studyCenterId': '$studyCenterId',
                    'studyGroupId': '$studyGroupId'
                }
            }).done(function (data){
                newTimetableLoader.hide();
                if(!data.success){
                    newTimetableErrorDisplay.html(data.message);
                    newTimetableErrorDisplay.show();
                }
            }).fail(function (data){
                newTimetableLoader.hide();
                newTimetableErrorDisplay.html(data.responseText);
                newTimetableErrorDisplay.show();
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
$this->registerJs($createTimetableJs, yii\web\View::POS_READY);


