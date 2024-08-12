<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use app\models\SmNameChange;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Name Change Requests';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-index">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
        <?= Html::encode($this->title) ?></h3>

    <p>
<!--        <?//= Html::a('Create Sm Name Change', ['create'], ['class' => 'btn btn-success']) ?>-->
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
          //  'current_surname',
           // 'current_othernames',

            [
                'attribute' => 'current_surname',
                'label' => 'Current Surname',
                'value' => 'current_surname'  ,'contentOptions' => [ 'style' => 'width: 15%;' ],
            ],
            [
                'attribute' => 'current_othernames',
                'label' => 'Current Other  Names',
                'value' => 'current_othernames',
                'contentOptions' => [ 'style' => 'width: 15%;' ],
            ],
            //'student.student_number',
            [
                'attribute' => 'student',
                'label' => 'Student Number',
                'value' => function($model) {
                    return $model['student_number'];
                },
                'contentOptions' => [ 'style' => 'width: 15%;' ],
            ],
//            'name_change_id',
            //'request_date',
            [
                'attribute' => 'request_date',
                'contentOptions' => [ 'style' => 'width: 15%;' ],
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model['request_date'], 'php:d-M-Y');
                },
            ],
            [
                'attribute' => 'status',
                'contentOptions' => [ 'style' => 'width: 15%;' ],

            ],
//            [
//                'attribute' => 'request_date',
//                'label' => 'Request Date',
//                'value' => 'request_date',
//                'contentOptions' => [ 'style' => 'width: 25%;' ],
//            ],
//            'student_id',
//            'new_surname',
//            'new_othernames',
            //'reason',
            //'document_url:url',

            //'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, SmNameChange $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'name_change_id' => $model->name_change_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',

                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' View Request and Act', ['/student-records/sm-name-change/view','name_change_id' => $model['name_change_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],


    ]); ?>


</div>
</div>
</div>
