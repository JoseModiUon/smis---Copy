<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgType;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Types';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-type-index">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Programme Type', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'prog_type_id',
                    //'prog_type_code',

                    [
                        'attribute' => 'prog_type_code',
                        'label' => 'Type Code',
                        //'value' => 'progCat.prog_cat_name',
                    ],
                    [
                        'attribute' => 'prog_type_name',
                        'label' => 'Type Name',
                        //'value' => 'progCat.prog_cat_name',
                    ],
                    [
                        'attribute' => 'prog_type_order',
                        'label' => 'Order',
                        //'value' => 'progCat.prog_cat_name',
                    ],

                    'status',
                    //            [
                    //                'class' => ActionColumn::className(),
                    //                'urlCreator' => function ($action, OrgProgType $model, $key, $index, $column) {
                    //                    return Url::toRoute([$action, 'prog_type_id' => $model->prog_type_id]);
                    //                 }
                    //            ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-prog-type/update', 'prog_type_id' => $model->prog_type_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>


        </div>