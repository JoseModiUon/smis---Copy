<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgCohort;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCohortSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cohorts';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-cohort-index">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Cohort',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

//                    [
//                        'attribute' => 'cohort_id',
//                        'label' => 'Cohort'
//                    ],
                    [
                        'attribute' => 'cohort_desc',
                        'label' => 'Cohort Description'
                    ],
                    'cohort_year', 
                    [
                        'attribute' => 'adm_start_date',
                        'value' => function ($model) {
                            if($model->adm_start_date) {
                                return strtoupper(Yii::$app->formatter->asDate($model->adm_start_date, 'php:d-M-yy')); 
                            }
                        },
                    ],
                    [
                        'attribute' => 'adm_end_date',
                        'value' => function ($model) {
                            if($model->adm_end_date) {
                                return strtoupper(Yii::$app->formatter->asDate($model->adm_end_date, 'php:d-M-yy'));
                            }
                        },
                    ],
                    //'cohort_status',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-cohort/update','cohort_id' => $model->cohort_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
                ]); 
            ?>
        </div>
    </div>
</div>
