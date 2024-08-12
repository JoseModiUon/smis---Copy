<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/27/2023
 * @time: 4:11 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $panelHeader
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\examManagement\models\search\MarksSearch $searchModel
 */

use app\modules\studentRegistration\helpers\SmisHelper;
use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
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
        <div class="row">
            <div class="col-10 offset-1">
                <div class="courses">
                    <div class="loader"></div>
                    <div class="error-display alert text-center" role="alert"></div>
                </div>
            <?php
            $idCol = [
                'attribute' => 'student_courses_id',
                'label' => 'REGISTRATION NO.',
                'vAlign' => 'middle',
                'width' => '15%'
            ];
            $regNumberCol = [
                'attribute' => 'course_registration_id',
                'label' => 'REGISTRATION NO.',
                'vAlign' => 'middle',
                'value' => function($model){
                    return explode('-', $model['course_registration_id'])['0'];
                },
                'width' => '15%'
            ];
            $courseMarkCol = [
                'attribute' => 'course_mark',
                'label' => 'CAT',
                'vAlign' => 'middle',
                'width' => '5%',
                'value' => function($model){
                    return (empty($model['course_mark'])) ? '--' : $model['course_mark'];
                }
            ];
            $examMarkCol = [
                'attribute' => 'exam_mark',
                'label' => 'EXAM',
                'vAlign' => 'middle',
                'width' => '5%',
                'value' => function($model){
                    return (empty($model['exam_mark'])) ? '--' : $model['exam_mark'];
                }
            ];
            $finalMarkCol = [
                'attribute' => 'final',
                'label' => 'FINAL',
                'vAlign' => 'middle',
                'width' => '5%',
                'value' => function($model){
                    return (empty($model['final_mark'])) ? '--' : $model['final_mark'];
                }
            ];
            $gradeCol = [
                'attribute' => 'grade',
                'label' => 'GRADE',
                'vAlign' => 'middle',
                'width' => '5%',
                'value' => function($model){
                    return (empty($model['grade'])) ? '--' : $model['grade'];
                }
            ];
            $examTypeCol = [
                'attribute' => 'examtype_code',
                'label' => 'EXAM TYPE',
                'vAlign' => 'middle',
                'width' => '10%',
                'value' => function($model){
                    return (empty($model['examtype_code'])) ? '--' : $model['examtype_code'];
                }
            ];
            $remarksCol = [
                'attribute' => 'remarks',
                'label' => 'REMARKS',
                'vAlign' => 'middle',
                'value' => function($model){
                    return (empty($model['remarks'])) ? '--' : $model['remarks'];
                }
            ];
            $lastUpdateCol = [
                'attribute' => 'last_update',
                'label' => 'LAST UPDATE',
                'vAlign' => 'middle',
                'width' => '21%',
                'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'presetDropdown'=>true,
                    'convertFormat'=>true,
                    'includeMonthsFilter'=>true,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'locale' => ['format' => 'Y-m-d'],
                        'separator'=>' to '
                    ],
                    'options' => [
                        'id' => 'marks-grid-last-update'
                    ]
                ],
                'value' => function($model){
                    return (empty($model['last_update'])) ? '--' : SmisHelper::formatDate($model['last_update'], 'd-m-Y');
                }
            ];
            $count = 0;
            $gridColumns = [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => function($model, $key, $index, $widget) use (&$count){
                        $count++;
                        return [
//                            'value' => $count
                            'value' => $model['student_courses_id']
                        ];
                    }
                ],
                $idCol,
                $regNumberCol,
                $courseMarkCol,
                $examMarkCol,
                $finalMarkCol,
                $gradeCol,
                $examTypeCol,
                $remarksCol,
                $lastUpdateCol
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
                    'id' => 'marks-grid',
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
                        'label' => 'Export students'
                    ],
                    'panel' => [
                        'heading' => $panelHeader
                    ],
                    'persistResize' => false,
                    'toggleDataOptions' => ['minCount' => 50],
                    'itemLabelSingle' => 'student',
                    'itemLabelPlural' => 'students',
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
$this->render('postMarksToPublish', ['filterLevel' => 'students']);
