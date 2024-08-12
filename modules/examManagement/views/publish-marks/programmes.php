<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/24/2023
 * @time: 3:14 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $departName
 * @var yii\data\ActiveDataProvider $progDataProvider
 * @var app\modules\examManagement\models\search\ProgrammesSearch $progSearchModel
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
        <div class="row">
            <div class="col-10 offset-1">
                <?php
                $progCurrIdCol = [
                    'attribute' => 'prog_curriculum_id',
                    'label' => 'id',
                    'vAlign' => 'middle',
                ];
                $progCodeCol = [
                    'attribute' => 'progCurriculum.programme.prog_code',
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'width' => '15%'
                ];
                $progNameCol = [
                    'attribute' => 'progCurriculum.programme.prog_full_name',
                    'label' => 'NAME',
                    'vAlign' => 'middle'
                ];
                $actionsCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{courses}',
                    'contentOptions' => [
                        'style'=>'white-space:nowrap;',
                        'class'=>'kartik-sheet-style kv-align-middle'
                    ],
                    'buttons' => [
                        'courses' => function ($url, $model){
                            return Html::a('view courses',
                                Url::to([
                                    '/exam-management/publish-marks/first-stage-filters',
                                    'progCode' => $model['progCurriculum']['programme']['prog_code'],
                                    'progCurrId' => $model['prog_curriculum_id']
                                ]),
                                [
                                    'title' => 'View courses',
                                    'class' => 'btn-link',
                                ]
                            );
                        }
                    ],
                    'hAlign' => 'center',
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $progCodeCol,
                    $progNameCol,
                    $actionsCol
                ];

                try{
                    echo GridView::widget([
                        'id' => 'programmes-grid',
                        'dataProvider' => $progDataProvider,
                        'filterModel' => $progSearchModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => [
                            '{export}',
                            '{toggleData}',
                        ],
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export programmes'
                        ],
                        'panel' => [
                            'heading' => 'PROGRAMMES IN THE DEPARTMENT OF ' . $departName,
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'programme',
                        'itemLabelPlural' => 'programmes',
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
