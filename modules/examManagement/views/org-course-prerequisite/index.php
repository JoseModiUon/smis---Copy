<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgCoursePrerequisite;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCoursePrerequisiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Level Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-course-prerequisite-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create  Level Requirements',
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
                        'label' => 'Course Grouping',
                        'value' => function($model) {
                            return $model['prog_curriculum_desc']." - ".$model['course_name'];
                        }
                    ],

                    [
                        'attribute' => 'course',
                        'label' => 'Min Courses Taken',
                        'value' => function($model) {
                            return $model['course_name'];
                        }
                    ],
                    [
                        'attribute' => 'Min Pass Courses',

                        'value' => function($model) {
                            return $model['status'];
                        }

                    ],

                    [
                        'attribute' => 'Set Course',

                        'value' => function($model) {
                            return $model['status'];
                        }

                    ],

                    [
                        'attribute' => 'Remove',

                        'value' => function($model) {
                            return $model['status'];
                        }

                    ],

                    [
                        'attribute' => 'Sr.No',

                        'value' => function($model) {
                            return $model['status'];
                        }

                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-course-prerequisite/update','course_prerequisite_id' => $model['course_prerequisite_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
