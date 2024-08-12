<?php
/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\OrgProgCurrCourseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $progCurrGroupReqId */



use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Add Programme Curriculum Courses';

$this->params['breadcrumbs'][] = $this->title;


?>
<div>
<div class="row">
    <div class="col">
           <?php // echo Html::a('<i class="fa-solid fa-rotate-left"></i> Return to Programme curriculum list', ['class' => 'submit btn btn-success btn-lg', 'style'=>'float:right']) ;
            echo Html::a(
                '<i class="fa-solid fa-rotate-left"></i> Return to Programme Requirement List',
                ['/exam-management/prog-curr-grp-req-course',
                'prog_code' => $_GET['prog_code'],
                'pcgrcid' => $_GET['prog_curr_group_requirement_id'],
                'prog_study_level' => $_REQUEST['prog_study_level'],
                'prog_curriculum_id' => $_GET['prog_curriculum_id']],
                ['class' => 'btn btn-outline-success btn-sm','style' => 'float:right']
            );?>
			</div>
    <div class="org-prog-curr-course-index">
        <?php
        $toolbar = [
            [
                'content' =>
                    Html::button('Add courses', [
                        'title' => 'Add courses',
                        'id' => 'add-courses-btn',
                        'class' => 'btn btn-success btn-spacer btn-sm',
                    ]),
                'options' => ['class' => 'btn-group mr-2']
            ],
            '{export}',
            '{toggleData}',
        ];

echo GridView::widget([
    'id' => 'courses-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
    'pjax' => true,
    'responsiveWrap' => false,
    'condensed' => true,
    'hover' => true,
    'striped' => false,
    'bordered' => false,
    'toolbar' => $toolbar,
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'export' => [
        'fontAwesome' => true,
        'label' => 'Export courses'
    ],
    'panel' => [
        'heading' => 'ADD COURSES TO GROUP ',
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 50],
    'itemLabelSingle' => 'course',
    'itemLabelPlural' => 'courses',
    'columns' => [
        ['class' => '\kartik\grid\SerialColumn'],
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $widget) {
                return [
                   'value' => $model['prog_curriculum_course_id'] . '-' . $model['credit_factor']
                ];
            }
        ],
        'course_code',
        'course_name',
        'credit_hours',

        'credit_factor',
/*[
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update}',
            'contentOptions' => [
                'style' => 'white-space:nowrap;',
                'class' => 'kartik-sheet-style kv-align-middle'
            ],
            'buttons' => [
                'update' => function ($url, $model, $key){
                    return  Html::a('<i class="fa-solid fa-plus" aria-hidden="true"></i> add course',
                        [
                            '/exam-management/org-prog-curr-course/index',
                            'prog_curr_group_requirement_id'=>$_GET['prog_curr_group_requirement_id'],
                            'prog_curriculum_course_id' => $model['prog_curriculum_course_id'],
                            'credit_factor' => $model['credit_factor'],
                        ],
                        [
                            'class' => 'btn btn-link',
                            'title' => 'Add programme curriculum course'
                        ]
                    );
                },
            ],
            'hAlign' => 'center',
                ],*/
    ],
]);
?>
    </div>
</div>

<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 11:41 AM
 */

$addCoursesUrl = Url::to(['/exam-management/org-prog-curr-course/add-course-to-requirement',
    'prog_code' => $_GET['prog_code'],
    'pcgrcid' => $_GET['prog_curr_group_requirement_id'],
    'prog_study_level' => $_REQUEST['prog_study_level'],
    'prog_curriculum_id' => $_GET['prog_curriculum_id']]);


$grid = '#courses-grid';
$coursesJs = <<< JS
const grid = '$grid';
const addCoursesUrl  = '$addCoursesUrl';
const progCurrGroupReqId = '$progCurrGroupReqId';


$(grid + '-pjax').on('click', '#add-courses-btn', function (e){
    e.preventDefault();
    let ids = getSelectedIds(grid);
    if(ids.length === 0){
        console.error('No courses have been selected');
    }else{
        if(confirm('Add courses?')){ 
           postData = {
                  'progCurrGroupReqId': progCurrGroupReqId,
                'progCurrCourseIds': ids
                
            }
            $.ajax({
                url: addCoursesUrl,
                type: 'POST',
                data: postData
            }).fail(function (data){
              console.error(data.responseText);
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
$this->registerJs($coursesJs, yii\web\View::POS_READY);
