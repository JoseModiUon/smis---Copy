<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use app\models\OrgProgCurrUnit;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Units';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-unit-index">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">

                    <?= Html::a('<i class="bi bi-plus-lg"></i> Create Programme  Curriculum Unit', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'prog_curriculum_unit_id',
            [
                'attribute' => 'orgUnitHistory',
                'label' => 'Unit',
                'value' => function($model) {
        //dd($model);
         return $model['orgUnitHistory']['orgUnit']['unit_name'];
                }
            ],
//            'org_unit_history_id',


//            'prog_curriculum_id',
            [
                'attribute' => 'progCurriculum',
                'label' => 'Curriculumn',
                'value' => function($model) {
//                    dd($model);
                     return $model['progCurriculum']['prog_curriculum_desc'];
                }
            ],
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
            //'status',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgProgCurrUnit $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/functionalSetup/org-prog-curr-unit/update','prog_curriculum_unit_id' => $model->prog_curriculum_unit_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
