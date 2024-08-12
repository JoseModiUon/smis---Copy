<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgLevel;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Levels';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-level-index">

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Programme Level', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>


            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?></h3>
            </div>




            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'programme_level_id',
                    'programme_level_name',
                    //            [
                    //                'class' => ActionColumn::className(),
                    //                'urlCreator' => function ($action, OrgProgLevel $model, $key, $index, $column) {
                    //                    return Url::toRoute([$action, 'programme_level_id' => $model->programme_level_id]);
                    //                 }
                    //            ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-prog-level/update', 'programme_level_id' => $model->programme_level_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>