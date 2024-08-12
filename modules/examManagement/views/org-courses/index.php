<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgCourses;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Courses';

$this->params['breadcrumbs'][] = ['label' => 'examManagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-courses-index">
    <div class="card" >
        <div class="card-body">

    <div class="d-flex justify-content-end">

    </div>
    <h3 class="card-title mb-3">
        <?= Html::encode($this->title) ?></h3>





    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'course_id',
            'course_code',
            'course_name',
//            'level_of_study',
            [
                    'attribute' => 'academicLevels',
                'label' => 'Level of Study',
              'value' =>  'academicLevels.academic_level_name',
            ],

            'semester',
            'academic_hours',

            //'org_unit_id',


            [
                'attribute' => 'orgUnit',
                'label' => 'Unit',
                'value' => 'orgUnit.unit_name',
            ],

//            [
//                'attribute' => 'prog_type_name',
//                'label' => 'Programme Type Name',
//                'value' => 'progCat.prog_cat_name',
//            ],
            'billing_factor',
//            'category_id',
            [
                'attribute' => 'category',
                'label' => 'Course Category',
                'value' => 'category.category_name',
            ],
            'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgCourses $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'course_id' => $model->course_id]);
//                 }
//            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Add Course', ['/exam-management/prog-curr-group-req-course/index','prog_curriculum_course_id' => $model->course_id ,'prog_curr_group_requirement_id' => Yii::$app->getRequest()->getQueryParam('prog_curr_group_requirement_id')], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],

        ],
    ]); ?>


</div>
</div>
</div>
