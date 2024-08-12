<?php


/* @var $this yii\web\View */
/* @var $searchModel app\modules\studentid\models\search\StudentIdRequestSearch */

/* @var $dataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
//    ['class' => 'kartik\grid\CheckboxColumn'],
    ['class' => 'kartik\grid\RadioColumn'],
    'reg_no',
    'full_name',
    'prog_full_name',
//    'request_type_id',
    'id_request_type',
//    'student_prog_curr_id',
    'request_date:date',
//    'status_id',
    'id_request_status',
    'request_reason',
//    'prog_type_id',
//    'prog_cat_id',
//    'prog_type_code',
//    'prog_type_name',
//    'prog_code',
//    'prog_id',
    'std_category_name',
//    'student_category_id',
//    'prog_curriculum_id',
    [
        'class' => 'kartik\grid\ActionColumn',
//        'header' => '#',
        'template' => '{update}',
        'buttons' => [
            'update' => function ($url, $model) {
                Yii::$app->log->logger->log("Accessing $url", 'info');
                return Html::a('Print ID', [
                    'print-single', 'id' => $model['request_id']
                ], ['title' => 'Print single id', 'class' => 'btn btn-sm btn-outline-success']);
            }
        ]
    ],
];

$this->params['breadcrumbs'][] = ['label' => 'Manage student ID', 'url' => ['/student-id']];
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?= $this->render('_id-request-search', ['model' => $searchModel]) ?>
            </div>
        </div>
    </div>
</section>


<div class="row mt-5">
    <div class="col">
        <?= Html::beginForm(['print-bulk']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'floatHeader' => true,
            'pjax' => false,
            'condensed' => true,
            'striped' => true,
            'bordered' => true,
            'panel' => [
                'type' => GridView::TYPE_DARK,
                'heading' => $this->title,
//                'before' => '<div style="padding-top: 7px;"><em>* You can select multiple id requests for processing</em></div>',
            ],
            'toolbar' => [
//                [
//                    'content' =>
//                        Html::submitButton('Print selected', [
//                            'class' => 'btn btn-success hidden',
//                            'title' => 'Print selected ids',
//                            'onclick' => 'alert("This will print multiple IDs in PDF");'
//                        ]),
//                    'options' => ['class' => 'btn-group mr-2 me-2']
//                ],
                '{toggleData}',
            ],
            'export' => false,
            'toggleDataOptions' => ['minCount' => 50],
            'itemLabelSingle' => 'pending request',
            'itemLabelPlural' => 'pending requests'
        ]); ?>
        <?= Html::endForm(); ?>
    </div>
</div>

