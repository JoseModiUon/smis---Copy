<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/27/2024
 * @time: 10:23 AM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string[] $years
 * @var string[] $programs
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 * @var string[] $activeFilters
 * @var string $panelHeader
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\examManagement\models\search\StudentsToProgressSearch $searchModel
 * @var array $programsCurr
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $title;
?>

    <!-- Content Header (Page header) -->
<?php
$breadcrumbUrls = [
    'Exam management' => Url::to(['/exam-management']),
    'progress students' => null
];
echo SmisHelper::createBreadcrumb($breadcrumbUrls);
?>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?= $this->render('filters', [
                'years' => $years,
                'programs' => $programs,
                'academicLevels' => $academicLevels,
                'groups' => $groups,
                'semesters' => $semesters,
                'programsCurr' => $programsCurr,
            ]); ?>

            <div class="row">
                <div class="col-10 offset-1">
                    <?php
                    $regNumberCol = [
                        'attribute' => 'regNumber',
                        'label' => 'REG NUMBER',
                        'vAlign' => 'middle',
                        'value' => function ($model) {
                            return $model['student_number'];
                        }
                    ];
                    $surnameCol = [
                        'attribute' => 'surname',
                        'label' => 'SURNAME',
                        'vAlign' => 'middle',
                        'value' => function ($model) {
                            return $model['surname'];
                        }
                    ];
                    $otherNamesCol = [
                        'attribute' => 'otherNames',
                        'label' => 'NAME',
                        'vAlign' => 'middle',
                        'value' => function ($model) {
                            return $model['other_names'];
                        }
                    ];
                    $semesterProgressCol = [
                        'label' => 'PROGRESS',
                        'vAlign' => 'middle',
                        'value' => function ($model) {
                            $studProgCurrId = $model['student_prog_curriculum_id'];
                        }
                    ];
                    $gridColumns = [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $widget) {
                                return [
                                    'value' => $model['student_number']
                                ];
                            }
                        ],
                        $regNumberCol,
                        $surnameCol,
                        $otherNamesCol
                    ];

                    $toolbar = [
                        [
                            'content' =>
                                Html::button('Progress students to next semester <i class="fa-solid fa-forward"></i> ', [
                                    'title' => 'Move students to the next semester',
                                    'id' => 'progress-btn',
                                    'class' => 'btn btn-success btn-spacer btn-sm',
                                ]),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                    ];

                    echo GridView::widget([
                        'id' => 'students-grid',
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
                    ?>
                </div>
            </div>
        </div>
    </section>

<?php
$progCurrSemGroupId = $activeFilters['semesterId'];
$saveStudentsProgressUrl = Url::to(['/exam-management/progression/save-progress']);

$progressStudentsJs = <<< JS
const saveStudentsProgressUrl = '$saveStudentsProgressUrl';

$('#students-grid-pjax').on('click', '#progress-btn', function (e) {
    e.preventDefault();
    
    let progCurrSemGroupId = $('#semester-id').val();
    if(progCurrSemGroupId === null || progCurrSemGroupId === '' ) {
        alert('Current semester must be provided in the filters.');
        return;
    }
    
    let students = getSelectedIds('#students-grid');
    if(students.length === 0){
        alert('No students have been selected.');
    }else{
         if(confirm('Progress these students?')){
             if(confirm('Are you sure about this action?')){
                courseFiltersErrorDisplay.hide();
                courseFiltersLoader.show(); 
                $.ajax({
                    url: saveStudentsProgressUrl,
                    type: 'POST',
                    data: {
                        'progCurrSemGroupId': progCurrSemGroupId, 
                        'students' : students
                    }
                }).done(function (data){
                    courseFiltersLoader.hide();
                    if(!data.success){
                        courseFiltersErrorDisplay.html(data.message);
                        courseFiltersErrorDisplay.show();
                    }
                }).fail(function (data){
                    courseFiltersLoader.hide();
                    courseFiltersErrorDisplay.html(data.responseText);
                    courseFiltersErrorDisplay.show();
                });   
             }
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
$this->registerJs($progressStudentsJs, yii\web\View::POS_READY);



