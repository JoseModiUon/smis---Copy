<?php

use app\modules\examManagement\models\ProgCurrCourseGroup;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\search\ProgCurrCourseGroupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Programme Curriculum Course Groups';
$this->params['breadcrumbs'][] = ['label' => 'Exam Mananagement', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-md-center">
    <div class="col col-md-10 bg-light">
        <div class="clearfix">&nbsp;</div>
<div class="prog-curr-course-group-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Create Programme Curriculum Course Group', ['create'], ['class' => 'btn btn-success','style' => 'float:right']) ?>
    </p>
    <div class="clearfix">&nbsp;</div>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' =>  'course_group_name',
                'value' =>  'course_group_name',
                'label' =>  'Course Group Name',


            ],

            [
                'attribute' =>  'course_group_desc',
                'value' =>  'course_group_desc',
                'label' =>  'Course Group Description',


            ],

            [
              'attribute' =>  'course_group_type',
              'value' =>  'course_group_type',
              'label' =>  'Course Group Type',


            ],
            [
                'attribute' => 'buttons',
                'label' => ' ',
                //'width'=>'80px',
                'format' => 'raw',
                'value' => function ($model) {
                    //return strtoupper($model['gender']);
                    //return '';
                    return Html::a('Update', ['update', 'course_group_id' => $model->course_group_id], ['class' => 'btn btn-outline-success btn-sm']);
                }
            ],

            /*[
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ProgCurrCourseGroup $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'course_group_id' => $model->course_group_id]);




                 }
            ],*/
        ],
    ]); ?>


</div>
</div>
</div>
