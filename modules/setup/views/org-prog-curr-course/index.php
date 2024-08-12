<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrCourse;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Courses';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-course-index">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

            <div class="d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-plus-lg"></i> Create Programme Curriculum Course', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'prog_curriculum_course_id',
//            'prog_curriculum_id',
            [
                'attribute' => 'progCurriculum',
                'label' => 'Programme Curriculum',
                'value' => 'progCurriculum.prog_curriculum_desc',
            ],
            [
                'attribute' => 'course',
                'label' => 'Course',
                'value' => 'course.course_name',
            ],


//            'course_id',
//            'course.course_name',
            'credit_factor',
            'credit_hours',
           // 'level_of_study',
            [
                'attribute' => 'academicLevels',
                'label' => 'Level of Study',
                'value' =>  'academicLevels.academic_level_name',
            ],
            'semester',
//            'prerequisite',
            
            'status',
            //'has_course_work:boolean',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgProgCurrCourse $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'prog_curriculum_course_id' => $model->prog_curriculum_course_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-prog-curr-course/update','prog_curriculum_course_id' => $model->prog_curriculum_course_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
