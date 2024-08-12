<?php

use app\models\ExGradingSystem;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ExGradingSystemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Grading System';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-grading-system-index">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Grading System',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'grading_system_name',
                    'grading_system_desc',
                    'status',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {details}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Update',
                                    [
                                        '/student-records/ex-grading-system/update',
                                        'grading_system_id' => $model->grading_system_id
                                    ],
                                    ['class' => ' bi btn btn-outline-primary bi-pencil-square']
                                );
                            },
                            'details' => function ($url, $model, $key) {
                                return  Html::a(
                                    'details',
                                    [
                                        '/student-records/ex-grading-system-detail/', 'grading_system_id' => $model->grading_system_id
                                    ],
                                    ['class' => ' bi  btn btn-outline-secondary bi bi-three-dots-vertical ']
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>