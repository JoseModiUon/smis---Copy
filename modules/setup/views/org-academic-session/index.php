<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgAcademicSession;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgAcademicSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Sessions';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-academic-session-index">
    <div class="card" >
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Add Academic Session',
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

                    // 'acad_session_id',
                    [
                        'attribute' => 'acad_session_name',
                        'label' => 'Name'
                    ],
                    [
                        'attribute' => 'acad_session_desc',
                        'label' => 'Description'
                    ],
                    'start_date',
                    'end_date',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-academic-session/update','acad_session_id' => $model->acad_session_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
