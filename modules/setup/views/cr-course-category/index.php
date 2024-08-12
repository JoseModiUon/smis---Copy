<?php

use app\models\CrCourseCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\CrCourseCategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Course Categories';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cr-course-category-index">

    

   <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
        <?= Html::a('+ Create  Course Category', ['create'], ['class' => 'btn btn-primary']) ?>
   </div>
   <h3><?= Html::encode($this->title) ?></h3>
  

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'category_id',
            'category_name',
            
			[
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/cr-course-category/update','category_id' => $model->category_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
        ],
    ]); ?>

 </div>
   </div>
</div>
