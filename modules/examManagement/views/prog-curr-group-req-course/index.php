<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
//use app\models\ProgCurrGroupReqCourse;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProgCurrLevelRequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Required Courses';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
            <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Requiremed Courses',
                    ['/exam-management/prog-curr-group-req-course/create','prog_curr_group_requirement_id' => Yii::$app->getRequest()->getQueryParam('prog_curr_group_requirement_id'),'prog_curriculum_course_id' => Yii::$app->getRequest()->getQueryParam('prog_curriculum_course_id')],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    'course_name',

                    [
                        'attribute' =>'prog_curr_group_requirement_id',
                        'label' => 'Group Requirement ID',
                        'value' => 'prog_curr_group_requirement_id',
                    ],

                    [
                        'attribute' => 'prog_curriculum_course_id',
                        'label' => 'Course Name',
                        'value' => 'prog_curriculum_course_id',
                        
                    ],

                    [
                        'attribute' => 'credit_factor',
                        'label' => 'Credit Factor',
                        'value' => 'credit_factor',

                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/exam-management' ], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
