<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use app\models\OrgUnitTypes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgUnitTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organisation Unit Types';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-types-index">

    <div class="card" >
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Organisation Unit Type', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>

            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>

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
            ['class' => 'yii\grid\SerialColumn'],

           // 'unit_type_id',
            'unit_type_name',
            'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgUnitTypes $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'unit_type_id' => $model->unit_type_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-unit-types/update','unit_type_id' => $model->unit_type_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
