<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\ProgCurrGroupRequirement;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProgCurrLevelRequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Course Group Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Group Requirements',
                    ['/exam-management/prog-curr-group-requirement/create', 'prog_curr_level_req_id' => Yii::$app->getRequest()->getQueryParam('prog_curr_level_req_id')],
                    ['class' => 'btn btn-lg btn-primary']
                ) ?>

            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([

                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,

                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    //'prog_curr_group_requirement_id',
                    //'prog_curr_level_req_id',
                    //'prog_curr_course_group_id',
                    [
                        'attribute' => 'course_group_name',
                        'label' => 'Course Group',
                        'value' => 'course_group_name',
                    ],

                    [
                        'attribute' => 'min_group_courses',
                        'label' => 'Minimum Group Courses',
                        'value' => 'min_group_courses',
                    ],

                    [
                        'attribute' => 'group_pass_type',
                        'label' => 'Group Pass Type',
                        'value' => 'group_pass_type',
                    ],

                    [
                        'attribute' => 'min_group_pass',
                        'label' => 'Minimum Group Pass',
                        'value' => 'min_group_pass',
                    ],



                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Required Courses', ['/exam-management/org-courses', 'prog_curr_group_requirement_id' => $model['prog_curr_group_requirement_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ]
            ]); ?>
        </div>
    </div>
</div>