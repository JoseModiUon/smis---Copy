<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgAcadSessionStatus;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgAcadSessionStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Session Statuses';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-acad-session-status-index">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Academic Session Status',
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
                        [
                            'attribute' => 'acad_session_status_name',
                            'label' => 'Name'
                        ],
                        [
                            'attribute' => 'session_status',
                            'label' => 'Status'
                        ],
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{update} ',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return  Html::a(' Update', ['/setup/org-acad-session-status/update','acad_session_status_id' => $model->acad_session_status_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                },
                            ]

                        ],
                    ],
                    ]); 
                ?>
        </div>
    </div>

</div>
