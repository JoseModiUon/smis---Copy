<?php

use app\models\SmWithdrawalRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalRequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Withdrawal & Deferment Request Approval';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-request-index">

    <h3><?= Html::encode($this->title) ?></h3>
   

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export'=>false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'withdrawal_request_id',
            //'withdrawal_type_id',
             [
                  'attribute' => 'student_number',
                'label' => 'Student Number',
                'value' => 'student.student_number',
            ],
            [
                  'attribute' => 'student_surname',
                'label' => 'Student Surname',
                'value' => 'student.surname',
            ],
            [
                  'attribute' => 'student_othernames',
                'label' => 'Student Other Names',
                'value' => 'student.other_names',
            ],
            /*[
                           'attribute' => 'student_name',
                'label' => 'Student Name',
                        'value' => function($model) {
                            return $model->student['surname']."  ".$model->student['other_names'];
                        }
                    ],*/
               [
                  'attribute' => 'smWithdrawalType',
                'label' => 'Type',
                'value' => 'smWithdrawalType.withdrawal_type_name'
            ],


            [
                            'attribute' => 'request_date',
                            'value' => function ($model) {
                                return strtoupper(Yii::$app->formatter->asDate($model->request_date, 'php:d-M-yy'));
                            },
                        ],


            //'reason',
            'approval_status',
            //'student_id',



            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Act on Request', ['/student-records/sm-withdrawal-request/view','withdrawal_request_id' => $model->withdrawal_request_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
