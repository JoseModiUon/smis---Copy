<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/12/2024
 * @time: 12:06 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var yii\data\ActiveDataProvider $programsProvider
 * @var app\modules\feesManagement\models\search\ProgCurrChargesSearch $filterModel
 * @var string $panelHeader
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
    'Fees management' => Url::to(['/fees-management']),
    'Programs' => null
];
echo SmisHelper::createBreadcrumb($breadcrumbUrls);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <?php
                $progCodeCol = [
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'attribute' => 'prog_code'
                ];
                $progNameCol = [
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'attribute' => 'prog_short_name'
                ];
                $billingTypeCol = [
                    'label' => 'BILLING TYPE',
                    'vAlign' => 'middle',
                    'attribute' => 'billing_type_desc'
                ];

                $actionCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'ACTIONS',
                    'template' => '{charges}',
                    'contentOptions' => ['style' => 'white-space:nowrap;', 'class' => 'kartik-sheet-style kv-align-middle'],
                    'buttons' => [
                        'charges' => function ($url, $model) {
                            return Html::a('list charges',
                                Url::to([
                                    '/fees-management/program-charges/list-charges',
                                    'progCurrId' => $model['prog_curriculum_id']
                                ]),
                                [
                                    'title' => 'View program charges',
                                    'class' => 'btn btn-link',
                                    'target' => '_blank',
                                    'data-pjax' => '0'
                                ]
                            );
                        }
                    ]
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $progCodeCol,
                    $progNameCol,
                    $billingTypeCol,
                    $actionCol
                ];

                echo GridView::widget([
                    'id' => 'prog-charges-grid',
                    'dataProvider' => $programsProvider,
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
                    'export' => false,
                    'panel' => [
                        'heading' => $panelHeader
                    ],
                    'persistResize' => false,
                    'itemLabelSingle' => 'program',
                    'itemLabelPlural' => 'programs',
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
