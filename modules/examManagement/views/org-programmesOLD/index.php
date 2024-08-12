<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgrammes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgrammesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programmes';
$this->params['breadcrumbs'][] = ['label' => 'ExaminationManagement', 'url' => ['/examinationManagement']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-programmes-index">
    <div class="card" >
        <div class="card-body">

        <!-- 
            <div class="d-flex justify-content-end">
                 <?= Html::a('<i class="bi bi-plus-lg"></i> Create Programmes', ['/setup/org-programmes/create'], ['class' => 'btn btn-primary']) ?>
            </div>
            --> 
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'prog_id',

       //     [
         //       'attribute' => 'prog_code',
           //     'label' => 'Code',

           // ],

         //   [
         //       'attribute' => 'prog_short_name',
         //       'label' => 'Short Name',

           // ],

            [
                'attribute' => 'prog_full_name',
                'label' => 'Course Name',
                //'value' => 'progCat.prog_cat_name',
            ],
//            'prog_type_id',
            [
                'attribute' => 'progType',
                'label' => 'Type',
                'value' => 'progType.prog_type_name',
            ],
//            'prog_cat_id',
            [
                'attribute' => 'progCat',
                'label' => 'Category',
                'value' => 'progCat.prog_cat_name',
            ],

            'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgProgrammes $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'prog_id' => $model->prog_id]);
//                 }
//            ],


            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Level Requirements', ['/examinationManagement/prog-curr-level-requirement/index','prog_curriculum_id' => $model->prog_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    }
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
