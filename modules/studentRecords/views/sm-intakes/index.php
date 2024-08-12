<?php

use app\models\OrgAcademicSession;
use app\models\SmIntakes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SmIntakesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Semester Intakes';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-intakes-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Intake',
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

                    'intake_name',
                    [
                        'attribute' => 'acad_session_id',
                        'label' => 'Academic Session',
                        'value' => function($model) {
                            return OrgAcademicSession::findOne($model->acad_session_id)->acad_session_name;
                        }
                    ],

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
                                    ' Update',
                                    [
                                        '/student-records/sm-intakes/update',
                                        'intake_code' => $model->intake_code
                                    ],
                                    ['class' => ' bi btn btn-outline-primary bi-pencil-square']
                                );
                            },
                        ]
                    ],

                ],
            ]); ?>
        </div>
    </div>



    </div>