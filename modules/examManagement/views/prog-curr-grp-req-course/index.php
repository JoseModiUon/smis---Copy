<?php

use app\modules\examManagement\models\ProgCurrGroupReqCourse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrGroupReqCourseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Programme Curriculum  Group Required Courses';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = ['label' => 'Programme requierements'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row justify-content-md-center">
        <div class="col col-md-10 bg-light">
            <div class="clearfix">&nbsp;</div>
           <div class="row">
               <div class="col-5">
            <?= Yii::$app->session->getFlash('msg') ?>
           </div>
           </div>
 <div class="col">
                <?php

                echo  '<div class="row">
    <div class="col">';
// echo Html::a('<i class="fa-solid fa-rotate-left"></i> Return to Programme curriculum list', ['class' => 'submit btn btn-success btn-lg', 'style'=>'float:right']) ;
echo Html::a(
    '<i class="fa-solid fa-rotate-left"></i> Return to Programme Requirement List',
    ['/exam-management/programmes/programme-requirements',
    'prog_code' => $_GET['prog_code'],'prog_curriculum_id' => $_GET['prog_curriculum_id']],
    ['class' => 'btn btn-outline-success btn-sm','style' => 'float:right']
);
echo '</div><p/> <hr> <div class="col">';
echo Html::a(
    '<i class="fa-solid"></i> Add Curriculum Course ',
    ['/exam-management/org-prog-curr-course',
    'prog_code' => $_REQUEST['prog_code'],
    'prog_curriculum_id' => $_REQUEST['prog_curriculum_id'],
    'prog_study_level' => $_REQUEST['prog_study_level'],
    'prog_curr_group_requirement_id' => $_REQUEST['pcgrcid']
    ],
    ['class' => 'btn btn-success btn-sm','style' => 'float:right']
);
?>
            </div>
            </div>													 
<div class="prog-curr-group-req-course-index">

    <h4><?= Html::encode($this->title) ?></h4>

  

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'course_code',
            'course_name',

            //'prog_curr_group_req_course_id',
            //'prog_curr_group_requirement_id',
            //'prog_curriculum_course_id',
            'credit_factor',
            [
'attribute' => 'action',
    'format' => 'raw',
'value' => function ($model) {
    /*   return Html::a('<i class="fa fa-trash"></i> Remove', ['delete',
            'id' => $model['prog_curr_group_requirement_id'],'prog_code'=>$_REQUEST['prog_code'],
            'prog_curriculum_id'=>$_REQUEST['prog_curriculum_id'],'prog_study_level'=>$_REQUEST['prog_study_level'],
            'prog_curr_group_req_course_id' =>$model['prog_curr_group_requirement_id']], ['class' => 'btn btn-outline-danger btn-sm',
           'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ]]
       );*/
    return Html::a(
        '<i class="fa fa-trash"></i> Remove Course',
        ['delete',

            'prog_study_level' => $_REQUEST['prog_study_level'],
            'prog_code' => $_REQUEST['prog_code'],
            'pcgrcid' => $_REQUEST['pcgrcid'],
            'prog_curriculum_id' => $_GET['prog_curriculum_id'],
            'prog_curr_group_req_course_id' => $model['prog_curr_group_req_course_id'

            ]],
        [
        'class' => 'btn btn-outline-danger',
        'data' => [
            'confirm' => 'Are you sure you want to remove '.$model['course_code'].'-'.$model['course_name'].'?',
            'method' => 'post',
        ],
                    ]
    );
}
            ],
            /*[
'class' => ActionColumn::className(),
'urlCreator' => function ($action, ProgCurrGroupReqCourse $model, $key, $index, $column) {
    return Url::toRoute([$action, 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id]);
 }
            ],*/
        ],
    ]); ?>


</div>
</div>
</div>
