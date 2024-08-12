<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 1:01 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $progName
 * @var string $progCode
 * @var string $level
 * @var string $progCurrId
 * @var string $year
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\examManagement\models\search\TimetablesSearch $searchModel
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Marks publishing <i class="fa fa-angle-right" aria-hidden="true"></i> Marks publishing</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <?= $this->render('secondStageFilters', [
                'year' => $year,
                'progCode' => $progCode,
                'level' => $level,
                'academicLevels' => $academicLevels,
                'groups' => $groups,
                'semesters' => $semesters,
                'progCurrId' => $progCurrId,
        ]); ?>

        <div class="row">
            <div class="col-10 offset-1">
                <div class="courses">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                </div>
                <?php
                $mrksheetIdCol = [
                    'attribute' => 'mrksheet_id',
                    'label' => 'id',
                    'vAlign' => 'middle',
                ];
                $courseCodeCol = [
                    'attribute' => 'programmeCurriculumCourse.course.course_code',
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'width' => '15%'
                ];
                $courseNameCol = [
                    'attribute' => 'programmeCurriculumCourse.course.course_name',
                    'label' => 'NAME',
                    'vAlign' => 'middle'
                ];
                $actionsCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{marks}',
                    'contentOptions' => [
                        'style'=>'white-space:nowrap;',
                        'class'=>'kartik-sheet-style kv-align-middle'
                    ],
                    'buttons' => [
                        'marks' => function ($url, $model){
                            return Html::a('view marks',
                                Url::to([
                                    '/exam-management/publish-marks/list',
                                    'marksheetId' => $model['mrksheet_id']
                                ]),
                                [
                                    'title' => 'View marks',
                                    'class' => 'btn-link',
                                ]
                            );
                        }
                    ],
                    'hAlign' => 'center',
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $widget) {
                            return [
                                'value' => $model['mrksheet_id']
                            ];
                        }
                    ],
                    $courseCodeCol,
                    $courseNameCol,
                    $actionsCol
                ];

                $toolbar = [
                    [
                        'content' =>
                            Html::button('Publish marks', [
                                'title' => 'Publish marks for selected courses',
                                'id' => 'publish-marks-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{export}',
                    '{toggleData}',
                ];

                try{
                    echo GridView::widget([
                        'id' => 'courses-grid',
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
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export courses'
                        ],
                        'panel' => [
                            'heading' => 'COURSES IN ' . $progName,
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'course',
                        'itemLabelPlural' => 'courses',
                    ]);
                }catch (Throwable $ex) {
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

<?php
$this->render('postMarksToPublish', ['filterLevel' => 'courses']);






