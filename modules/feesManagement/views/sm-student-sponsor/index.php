<?php

use app\models\SmStudentSponsor;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SmStudentSponsorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Student Sponsors';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-student-sponsor-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>
            

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Student Sponsor',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>

            <?php //echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    'sponsor_code',
                    'sponsor_name',
                    'status',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{receipt} {update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'receipt' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Receipt Funds ',
                                    [
                                        '/fees-management/receipt-sponsor-fund/',
                                        'sponsor_id' => $model->sponsor_id
                                    ],
                                    ['class' => ' bi btn-link bi bi-receipt text-success']
                                );
                            },
                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Update ',
                                    [
                                        '/fees-management/sm-student-sponsor/update',
                                        'sponsor_id' => $model->sponsor_id
                                    ],
                                    ['class' => ' bi btn-link bi-pencil-square text-primary']
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>