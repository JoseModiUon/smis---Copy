<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgKuccpsProgMapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kuccps Program Maps';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-kuccps-prog-map-index">


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end px-2">
                <div>
                    <?= Html::a(
                        '<i class="bi bi-plus-lg"></i> Create Kuccps Program Maps',
                        ['create'],
                        ['class' => 'btn btn-primary']) 
                    ?>
                </div>

                <div class="px-2">

                    <?= Html::a(
                        '<i class="bi bi-file-earmark-spreadsheet-fill"></i> Upload Excel File',
                        ['batch'],
                        ['class' => 'btn btn-primary']) 
                    ?>
                </div>

            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    // 'prog_map_id',
                    [
                        'attribute' => 'kuccps_prog_code',
                        'label' => 'Kuccps Program Code'
                    ],
                    [
                        'attribute' => 'kuccps_prog_name',
                        'label' => 'Kuccps Program Name'
                    ],
                    [
                        'attribute' => 'uon_prog_code',
                        'label' => 'UON Program Code'
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-kuccps-prog-map/update','prog_map_id' => $model->prog_map_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
