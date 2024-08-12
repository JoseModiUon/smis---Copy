<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgCountry;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-country-index">
    <div class="card">
        <div class="card-body">

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <?= Html::a('<i class="bi bi-plus-lg"></i> Create Country', ['create'], ['class' => 'btn btn-primary']) ?>
                    </div>


                    <div class="d-flex flex-column">
                        <h3 class="card-title mb-3">
                            <?= Html::encode($this->title) ?></h3>
                    </div>




                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' => false,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'country_code',
                            'country_name',
                            'continent',
                            'region',
                            'code2',
                            'nationality',
                            //            [
                            //                'class' => ActionColumn::className(),
                            //                'urlCreator' => function ($action, OrgCountry $model, $key, $index, $column) {
                            //                    return Url::toRoute([$action, 'country_code' => $model->country_code]);
                            //                 }
                            //            ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{update} ',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return  Html::a(' Update', ['/setup/org-country/update', 'country_code' => $model->country_code], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                    },
                                ]

                            ],
                        ],
                    ]); ?>


                </div>
            </div>
        </div>