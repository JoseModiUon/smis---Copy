<?php

use app\models\PaymentMode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\PaymentModeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Payment Modes';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-mode-index">

<div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>


            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Payment Mode',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                    ?>
            </div>

    <!-- <p>
        </?= Html::a('Create Payment Mode', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mode_code',
            'description',
            'mode_flag',
            // 'payment_mode_id',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, PaymentMode $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'payment_mode_id' => $model->payment_mode_id]);
            //      }
            // ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => [
                    'style' => 'white-space:nowrap',
                    'class' => 'kartik-sheet-style kv-align-middle'
                ],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(
                            ' Update ',
                            [
                                '/fees-management/payment-mode/update',
                                'payment_mode_id' => $model->payment_mode_id
                            ],
                            ['class' => ' bi-pencil-square btn btn-outline-primary btn-sm']
                        );
                    },
                ]
            ],
        ],
    ]); ?>
 </div>
    </div>

</div>
