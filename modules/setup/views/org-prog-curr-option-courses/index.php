<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrOptionCourses;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrOptionCoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Option Courses';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-option-courses-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Program Curriculum Option Courses',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    [
                        'attribute' => 'option',
                        'label' => 'Option Name',
                        'value' => 'option.option_name'
                    ],
                    [
                        'attribute' => 'courses',
                        'label' => 'Course Name',
                        'value' => 'courses.course_name'
                    ],

                    'course_type',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-prog-curr-option-courses/update','option_course_id' => $model->option_course_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
