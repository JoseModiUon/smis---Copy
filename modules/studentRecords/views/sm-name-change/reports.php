<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\OrgUnit;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use kartik\select2\Select2;
use app\models\SmNameChange;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Name Change Requests Reports';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-index">
    <div class="card" >
        <div class="card-body">

            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'orgUnit',
                        'group' => true,
                        'label' => 'Org Unit',
                        // 'contentOptions' => [ 'style' => 'width: 7%;' ],
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Org unit..'
                        ],
                        'value' => function($model){
                            // dd($model);
                            return $model['unit_name'];
                        }
                    ],
                    [
                        'attribute' => 'current_surname',
                        'label' => 'Current Surname',
                        'value' => 'current_surname'  ,'contentOptions' => [ 'style' => 'width: 10%;' ],
                    ],
                    
                    [
                        'attribute' => 'current_othernames',
                        'label' => 'Current Other  Names',
                        'value' => 'current_othernames',
                        'contentOptions' => [ 'style' => 'width: 12%;' ],
                    ],
                    [
                        'attribute' => 'student',
                        'label' => 'Student Number',
                        'contentOptions' => [ 'style' => 'width: 7%;' ],
                        'value' => function($model){
                            return $model['student_number'];
                        }
                    ],

                    [
                        'attribute' => 'new_surname',
                        'contentOptions' => [ 'style' => 'width: 10%;' ],

                    ],
                    [
                        'attribute' => 'new_othernames',
                        'contentOptions' => [ 'style' => 'width: 15%;' ],

                    ],
                    [
                        'attribute' => 'request_date',
                        'contentOptions' => [ 'style' => 'width: 10%;' ],
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model['request_date'], 'php:d-M-Y');
                        },
                    ],

                    [
                        'attribute' => 'status',
                        'contentOptions' => [ 'style' => 'width: 10%;' ],

                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',

                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                if ($model['status'] != 'PENDING') {
                                    return  Html::a(' View Approval Process', ['/student-records/sm-name-change-approval','name_change_id' => $model['name_change_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                }
                            },
                        ]

                    ],
                ],
                ]);
?>
        </div>
    </div>
</div>
