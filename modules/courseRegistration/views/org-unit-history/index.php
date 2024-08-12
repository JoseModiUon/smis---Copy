<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use app\models\OrgUnitHistory;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgUnitHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organisation Unit History';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-history-index">
    <div class="card" >
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Organisation Unit History', ['create'], ['class' => 'btn btn-primary']) ?>
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

//            'org_unit_history_id',
            [
                'attribute' => 'orgUnit',
                'label' => 'Unit Name',
                'value' => 'orgUnit.unit_name',
            ],


            //'org_type_id',
            [
                'attribute' => 'orgType',
                'label' => 'Unit Type',
                'value' => 'orgType.unit_type_name',
            ],
            //'parent_id',
            [
                'attribute' => 'parentUnit',
                'label' => 'Parent Unit',
                'value' => 'parentUnit.unit_name',
            ],
           // 'successor_id',
            [
                'attribute' => 'successorUnit',
                'label' => 'Successor Unit',
                'value' => 'successorUnit.unit_name',
            ],
            //'successorUnit.unit_name',
            [
                'attribute' => 'unitHead',
                'label' => 'Unit Head',
                'value' => 'unitHead.unit_head_name',
            ],

            //'unit_head_user_id',
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return strtoupper(Yii::$app->formatter->asDate($model->start_date, 'php:d-M-yy'));
                },
            ],
            // 'end_date',
            [
                'attribute' => 'end_date',
                'value' => function ($model) {
                    if(isset($model->end_date)):
                        return strtoupper(Yii::$app->formatter->asDate($model->end_date, 'php:d-M-yy'));
                    endif;
                },
            ],
            //'user_id',
            //'date_created',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgUnitHistory $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'org_unit_history_id' => $model->org_unit_history_id]);
//                 }
//            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(
                            ' Update',
                            ['/functionalSetup/org-unit-history/update', 'org_unit_history_id' => $model->org_unit_history_id],
                            ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']
                        );
                    },
                ]
            ],


        ],
    ]); ?>


</div>
</div>
</div>
