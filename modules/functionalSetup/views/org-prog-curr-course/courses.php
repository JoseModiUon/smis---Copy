<?php

use app\models\OrgCourses;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dialog\Dialog;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrCourse;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);


$savedIds = OrgProgCurrCourse::find()->select(['course_id'])->where(['prog_curriculum_id' => $prog_curriculum_id, 'status' => 'ACTIVE'])->all();
$assignedIds = ArrayHelper::getColumn($savedIds, 'course_id');

$query = OrgCourses::find();
$query->select('unit_name')->distinct();
$query->joinWith(['orgProgCurrCourses' => function (ActiveQuery $q) {
    $q->joinWith(['progCurriculum' => function (ActiveQuery $p) {
        $p->joinWith(['orgProgCurrUnits' => function (ActiveQuery $r) {
            $r->joinWith(['orgUnitHistory' => function (ActiveQuery $u) {
                $u->joinWith('orgUnit');
            }]);
        }]);
    }]);
}]);
$query->where(['org_programme_curriculum.prog_curriculum_id' => $prog_curriculum_id]);
$res = $query->asArray()->one();
$session = Yii::$app->session;

$this->title = 'Add Courses ';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Program Curriculum Courses', 'url' => ['/functionalSetup/org-prog-curr-course', 'OrgProgCurrCourseSearch[prog_curriculum_id]' => $session->get('prog_curriculum_id')]];
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'Summary Header',
        'width' => '310px',
        'value' => function ($model) {
            if ($model['academicLevels']) {
                $level = '<strong>Level of Study:</strong> ' . $model['academicLevels']['academic_level_name'];
                $semester = '<strong>Semester:</strong> ' . $model['semesterCode']['semster_name'];
                return $level . "&nbsp;&nbsp;&nbsp;&nbsp;" . $semester;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        // 'filter' => ArrayHelper::map(Suppliers::find()->orderBy('company_name')->asArray()->all(), 'id', 'company_name'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        // 'filterInputOptions' => ['placeholder' => 'Any supplier'],
        'group' => true,  // enable grouping,
        'groupedRow' => true,                    // move grouped column to a single grouped row
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
        'format' => 'raw'
    ],

    [
        'class' => '\kartik\grid\CheckboxColumn',
        'checkboxOptions' => function ($model) use ($assignedIds) {
            $bool = in_array($model['course_id'], $assignedIds);

            return [
                'checked' => $bool,
                'value' => $model['course_id']
            ];
        }
    ],
    'course_code',
    'course_name',

    'academic_hours',
    'billing_factor',
    [
        'attribute' => 'category',
        'label' => 'Course Category',
        'value' => 'category.category_name',
    ],
    'status',

];
?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>

            <?= GridView::widget([
                'id' => 'add-courses-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'columns' => $gridColumns,
                'toolbar' => [
                    '{toggleData}',
                    [
                        'content' =>
                        Html::button(
                            '<i class="fas fa-check"></i> Save Courses',
                            [
                                'title' => 'Save Courses',
                                'id' => 'save-courses-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]
                        ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>

<div id="app-is-loading-modal" class="modal fade app-is-loading" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md app-is-loading" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="app-is-loading-modal-title" class="modal-title">Processing...</h5>
            </div>
            <div class="modal-body">
                <div id="app-is-loading-message">
                    <h6 class="text-center spinner"><i class="fas fa-circle-notch fa-spin"></i></h6>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$urlSaveCourses = Url::to(['/functionalSetup/org-prog-curr-course/save-courses']);

$coursesJs = <<< JS
let urlSaveCourses = '$urlSaveCourses';
let prog_curriculum_id = '$prog_curriculum_id'


function getSelectedIds(){
    let keys = $('#add-courses-grid').yiiGridView('getSelectedRows');
    
    let ids = [];

    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');
        
        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    
    return [...new Set(ids)]
}


$('#add-courses-grid-pjax').on('click', '#save-courses-btn', function(e){
    let selectedCoursesIds = getSelectedIds();

    if(selectedCoursesIds.length === 0){
        krajeeDialog.alert('No courses were selected.');
    }else{
        krajeeDialog.confirm('Approve selected courses?', function (result) {
            if (result) {
                // $('#app-is-loading-modal').modal('show');
                                
                let form = $('#approve-students-form')[0];
                let data = {
                    prog_curriculum_id: prog_curriculum_id,
                    courseIds: selectedCoursesIds
                }
                
                $.ajax({
                    url: urlSaveCourses,
                    type: 'POST',
                    data: data,
                    dataType: 'json',  
                    success: function(data) {
                        // $('#app-is-loading-modal').modal('hide');
                        krajeeDialog.alert('Courses updated successfully!');
                    },
                    error: function(err) {
                        // $('#app-is-loading-modal').modal('hide');
                        krajeeDialog.alert('Courses update fail!');
                    }
                })
            }
        }); 
    }
});

JS;
$this->registerJs($coursesJs, yii\web\View::POS_READY);
