<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\studentid\models\search\StudentIdSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Manage student ID', 'url' => ['/student-id']];
$this->params['breadcrumbs'][] = $this->title;
?>


<h1><?= Html::encode($this->title) ?></h1>

<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    'full_name',
    'registration_number',
    'prog_full_name',
    'printing_date:date',
    'issuance_date:date',
    'valid_from:date',
    'valid_to:date',
    'barcode',
    'id_status',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{id-action}',
        'buttons' => [
            'id-action' => function ($url, $model) {

                Yii::info('Processing ' . $url);
                if ($model['id_status'] === \app\modules\studentid\models\StudentIdStatus::ID_ACTIVE) {
                    return Html::a('Reprint ID', [
                        'active-id/print-id', 'id' => $model['student_id_serial_no']
                    ], [
                        'class' => 'btn btn-success w-100',
                        'target' => '_blank',
                        'title' => 'Reprint ID',
                    ]);
                }
                return Html::a('Issue', [
                    'issue-ready-id', 'id' => $model['student_id_serial_no']
                ], [
                    'class' => 'btn btn-info w-100',
                    'title' => 'Issue ID',
                ]);
            },
        ],
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'visible' => false,
        'template' => '{export-id}',
        'buttons' => [
            'export-id' => function ($url, $model) {
                if ($model['id_status'] === \app\modules\studentid\models\StudentIdStatus::ID_ACTIVE) {
                    return Html::a('Export to PDF', [
                        'active-id/export-pdf', 'id' => $model['student_id_serial_no']
                    ], [
                        'class' => 'btn btn-outline-primary w-100',
                        'target' => '_blank',
                        'title' => 'Export id to PDF',
                    ]);
                }
                return '#';
            },
        ],
    ],
];
?>


<div class="row mt-5">
    <div class="col">
        <div class="row mt-5">
            <div class="col">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'pjax' => false,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-id']],
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => $this->title,
                    ],
                    'export' => false,
                    'toolbar' => [
                        '{toggleData}',
                    ],
                    'itemLabelSingle' => 'Student ID',
                    'itemLabelPlural' => 'Student IDs'
                ]); ?>

            </div>
        </div>
    </div>
</div>

</div>
</div>