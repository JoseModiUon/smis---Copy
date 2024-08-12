<?php

use app\models\ExMode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ExModeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Exam Mode';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-mode-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Exam Mode',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         ['class' => 'kartik\grid\SerialColumn'],

           // 'exam_mode_id',
            'exam_mode_name',
            /*[
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExMode $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'exam_mode_id' => $model->exam_mode_id]);
                 }
            ],*/
			[
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/ex-mode/update','exam_mode_id' => $model->exam_mode_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
        ],
    ]); ?>


	</div>
   </div>
</div>
