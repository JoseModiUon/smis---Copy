<?php

use yii\helpers\Html;
use yii\helpers\Url;
use Kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgAcademicLevelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Levels';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-academic-levels-index">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Add Academic Level',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
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

                    //  'academic_level_id',
                    'academic_level',
                    'academic_level_name',
                    'academic_level_order',
                    'status',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-academic-levels/update', 'academic_level_id' => $model->academic_level_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>