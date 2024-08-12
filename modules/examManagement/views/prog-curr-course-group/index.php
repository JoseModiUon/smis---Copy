<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\ProgCurrLevelRequirement;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProgCurrLevelRequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Course Groups';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create  Course Groups',
                    ['create'],
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

                    [
                        'attribute' => 'progCurCourse',
                        'label' => 'Program Curriculum',
                        'value' => function($model) {
                            return $model['prog_curriculum_id']; //." - ".$model['course_name'];
                        }
                    ],

                    [
                        'attribute' => 'course',
                        'label' => 'Min Courses Taken',
                        'value' => function($model) {
                            return $model['min_courses_taken'];
                        }
                    ],
                    [
                        'attribute' => 'Min Pass Courses',

                        'value' => function($model) {
                            return $model['min_pass_courses'];
                        }

                    ],

                    [
                        'attribute' => 'GPA Choice',

                        'value' => function($model) {
                            return $model['gpa_choice'];
                        }

                    ],

                    [
                        'attribute' => 'GPA Weight',

                        'value' => function($model) {
                            return $model['gpa_weight'];
                        }

                    ],

                    [
                        'attribute' => 'Pass Result',

                        'value' => function($model) {
                            return $model['pass_result'];
                        }

                    ],

                    [
                        'attribute' => 'Pass Recommendation',

                        'value' => function($model) {
                            return $model['pass_recommendation'];
                        }

                    ],

                    [
                        'attribute' => 'Fail type',

                        'value' => function($model) {
                            return $model['fail_type'];
                        }

                    ],

                    [
                        'attribute' => 'Fail Result',

                        'value' => function($model) {
                            return $model['fail_result'];
                        }

                    ],

                    [
                        'attribute' => 'Fail Recommendation',

                        'value' => function($model) {
                            return $model['fail_recommendation'];
                        }

                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/exam-management/prog-curr-level-requirement/update','prog_curr_level_req_id' => $model['prog_curr_level_req_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
