<?php



use app\models\FeeItems;



use yii\grid\ActionColumn;

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\web\ServerErrorHttpException;


/** @var yii\web\View $this */
/** @var app\models\search\FeeItemsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */



$this->title = $title;



echo SmisHelper::createBreadcrumb([
    'Fees Management' => Url::to(['/fees-management']),
    $title => null
]);
?>
<div class="section">
    <div class="container">
        <?php

        $feeDescription = [
            'label' => 'Fee Description',
            'value' => 'fee_description'
        ];

        $priority = [
            'label' => 'Priority',
            'value' => 'priority'
        ];

        $feeTypeCol = [
            'label' => 'Fee Type',
            'value' => 'fee_type'
        ];

        $publishCol = [
            'label' => 'Published?',
            'value' => 'publish'
        ];
        
        $narrationCol = [
            'label' => 'Narration',
            'value' => 'naration'
        ];

        $fee_code_alias = [
            'label' => 'Fee Code Alias',
            'value' => 'fee_code_alias'
        ];

        $actionButtons = [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
            'contentOptions' => [
                'style' => 'white-space:nowrap;',
                'class' => 'kartik-sheet-style kv-align-middle'
            ],
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return  Html::a(
                        '<i class="fa fa-pencil" aria-hidden="true"></i>',
                        [
                            '/fees-management/fee-items/update',
                            'fee_code' => $model['fee_code'],

                        ],
                        [
                            'class' => 'btn btn-link',
                            'title' => 'Update Fee Item'
                        ]
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fa-solid fa-trash text-danger"></i>', ['delete', 'fee_code' => $model->fee_code], [
                        'class' => 'btn btn-link',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                },
                
            ],
            'hAlign' => 'center',

        ];

        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            $feeDescription,
            $priority,
            $feeTypeCol,
            $publishCol,
            $fee_code_alias,
            $narrationCol,
            $actionButtons
        ];



        $toolbar = [
            [
                'content' =>
                Html::a(
                    '<i class="fa-solid fa-plus"></i> Create New Fee Item',
                    Url::to(['/fees-management/fee-items/create']),
                    [
                        'class' => 'btn btn-success btn-spacer btn-sm',
                    ]
                ),
                'options' => ['class' => 'btn-group mr-2']
            ],
        ];

        try {
            echo GridView::widget([
                'id' => 'fee-itesm-setup-grid',
                'dataProvider' => $dataProvider,
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
                // 'export' => [
                //     'fontAwesome' => true,
                //     'label' => 'Export Report'
                // ],
                'panel' => [
                    'heading' => '<h5 class="text-center">Fee Items</h5>'
                ],
                'persistResize' => false,
                'itemLabelSingle' => 'items',
                'itemLabelPlural' => 'items',
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