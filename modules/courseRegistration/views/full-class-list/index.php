<?php

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\search\FullClassListSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\modules\courseRegistration\models\CrProgCurrTimetable;
use app\modules\courseRegistration\models\search\FullClassListSearch;
use app\modules\courseRegistration\models\search\IndividualClassListSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\web\ServerErrorHttpException;
use kartik\grid\GridView;

$this->title = 'Full Class List';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <?php 
                [
                    'attribute' => 'Summary Header',
                    'width' => '310px',
                    'value' => function ($model) {
                        if (!empty($model)) {
                            return $model['academic_level_id'] . ' - ' . $model['semster_name'];
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
                ];

                $totalStudentCount = [
                    'attribute' => 'course_code',
                    'label' => 'No. of Students',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        $classList = (new IndividualClassListSearch())->search(['timetable_id' => $model['timetable_id']])->query->all();
                        return count($classList);
                    }
                ];

                $courseCodeCol = [
                    'attribute' => 'course_code',
                    'label' => 'Course Code',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['course_code'];
                    }
                ];
                $courseNameCol = [
                    'attribute' => 'course_name',
                    'label' => 'Course Name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['course_name'];
                    }
                ];
                $academicLevelCol = [
                    'attribute' => 'academic_level_name',
                    'label' => 'Academic Level',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['academic_level_name'];
                    }
                ];
                $acadSessionCol = [
                    'attribute' => 'acad_session_name',
                    'label' => 'Academic Session',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['acad_session_name'];
                    }
                ];
                $studyCentreCol = [
                    'label' => 'Study Centre',
                    'attribute' => 'study_centre_name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['study_centre_name'];
                    }
                ];
                $studyGroupCol = [
                    'label' => 'Study Group',
                    'attribute' => 'study_group_name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['study_group_name'];
                    }
                ];
                $semTypeCol = [
                    'label' => 'Semester Type',
                    'attribute' => 'semester_type',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['semester_type'];
                    }
                ];
                $semNameCol = [
                    'label' => 'Semester Name',
                    'attribute' => 'semster_name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['semster_name'];
                    }
                ];
                $progLevelCol = [
                    'label' => 'Programme Level',
                    'attribute' => 'programme_level',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programme_level'];
                    }
                ];
                $buttons = [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update}',
                    'contentOptions' => [
                        'style' => 'white-space:nowrap;',
                        'class' => 'kartik-sheet-style kv-align-middle'
                    ],
                    'buttons' => [
                        'update' => function ($url, $model, $key){
                            return  Html::a('<i class="fa fa-eye" aria-hidden="true"></i> class List',  
                                [
                                    '/courseRegistration/full-class-list/view-new',
                                    'timetable_id' => $model['timetable_id'],
                                ], 
                                [
                                    'class' => 'btn btn-link',
                                    'title' => 'View class list'
                                ]
                            );
                        },
                    ],
                    'hAlign' => 'center',

                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $courseCodeCol,
                    $courseNameCol,
                    $academicLevelCol,
                    $semNameCol,
                    $semTypeCol,
                    $acadSessionCol,
                    $studyCentreCol,
                    $studyGroupCol,
                    $totalStudentCount,
                    $buttons
                ];

                $toolbar = [
                    '{export}',
                    '{toggleData}',
                ];

                try{
                    echo GridView::widget([
                        'id' => 'timetable-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => true,
                        'bordered' => false,
                        'toolbar' => $toolbar,
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export Class List'
                        ],
                        'panel' => [
                            'heading' => '',
                        ],
                        'persistResize' => true,
                        'toggleDataOptions' => ['minCount' => 10],
                        'itemLabelSingle' => 'Course',
                        'itemLabelPlural' => 'Courses',
                    ]);
                }catch (\Throwable $ex) {
                    $message = $ex->getMessage();
                    if(YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
        </div>
    </div>
</section>

