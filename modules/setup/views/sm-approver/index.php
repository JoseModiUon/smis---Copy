<?php

use app\models\SmApprover;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SmApproverSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Approvers';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-approver-index">


     <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-plus-lg"></i> Create Approver', ['create'], ['class' => 'btn btn-primary']) ?>
 
	</div>
	  <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'approver_id',
            'approver',
            'level',
            'status',

				[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/sm-approver/update','approver_id' => $model->approver_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
			
			/*
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SmApprover $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'approver_id' => $model->approver_id]);
                 }
            ],*/
        ],
    ]); ?>


</div>
</div>
</div>
