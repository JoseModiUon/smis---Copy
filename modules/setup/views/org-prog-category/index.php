<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCategory;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Categories';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-category-index">
    <div class="card" >
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Category', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>

            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>



<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>
=======
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'prog_cat_id',

            [
                'attribute' => 'prog_cat_code',
                'label' => 'Category Code',
            ],
            [
                'attribute' => 'prog_cat_name',
                'label' => 'Category Name',
            ],
            [
                'attribute' => 'prog_cat_order',
                'label' => 'Order',
            ],

            'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgProgCategory $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'prog_cat_id' => $model->prog_cat_id]);
//                 }
//            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-prog-category/update','prog_cat_id' => $model->prog_cat_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
