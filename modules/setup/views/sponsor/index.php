<?php

/** author Jeff Rureri <wahome4jeff@gmail.com>
 * Date: 30/10/2023
*/

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\ServerErrorHttpException;

/** @var yii\web\View $this */
/** @var app\models\search\SponsorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Sponsors';

$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];

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
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'group' => true,  // enable grouping,
                    'groupedRow' => true,                    // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                    'format' => 'raw'
                ];
$sponsorName = [
    'attribute' => 'sponsor_name',
    'label' => 'Sponsor Name',
    'vAlign' => 'middle',
    'value' => function ($model) {
        return($model['sponsor_name']);
    }
];
$status = [

    'label' => 'Status',
    'vAlign' => 'middle',
    'value' => function ($model) {
        if ($model['status'] == 1) {
            return('Active');
        } else {
            return('Not Active');
        }
    }
];
$country = [
    'attribute' => 'country_name',
    'label' => 'Country',
    'vAlign' => 'middle',
    'value' => function ($model) {
        // dd($model);
        return($model['country_name']);
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
        'update' => function ($url, $model, $key) {
            return  Html::a(
                '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> update',
                [
                '/setup/sponsor/update',
                'sponsor_id' => $model['sponsor_id'],

                            ],
                [
                'class' => 'btn btn-link',
                'title' => 'Update Sponsor Record'
                            ]
            );
        },
    ],
    'hAlign' => 'center',

];

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    $sponsorName,
    $country,
    $status,
    $buttons
];

$toolbar = [
    [
        'content' =>
        Html::a(
            '<i class="bi bi-plus-lg"></i> Create Sponsor',
            ['create'],
            [
            'title' => 'Create new sponsor record',

            'class' => 'btn btn-success btn-spacer btn-sm',
                        ],
            [
            '/setup/sponsor/create',

                        ],
            [
            'class' => 'btn btn-link',
            'title' => 'Update Sponsor Record'
                        ]
        ),
        'options' => ['class' => 'btn-group mr-2']
    ],
    // '{export}',
    '{toggleData}',
];

try {
    echo GridView::widget([
        'id' => 'sponsor-grid',
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
            'label' => 'Export Sponsor List'
        ],
        'panel' => [
            'heading' => '',
        ],
        'persistResize' => true,
        'toggleDataOptions' => ['minCount' => 50],
        'itemLabelSingle' => 'Sponsor',
        'itemLabelPlural' => 'Sponsors',
    ]);
} catch (\Throwable $ex) {
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

