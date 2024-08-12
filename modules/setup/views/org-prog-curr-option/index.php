<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrOption;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrOptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Options';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-option-index">

    <div class="card" >
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Add Programme Curriculum Option', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    [
                        'attribute' => 'option_code',
                        'label' => 'Option Code',
                    ],
                    'option_name',
                    [
                        'attribute' => 'programmeCurriculum',
                        'label' => 'Curriculum Description',
                        'value' => function($model) {
                            return ($model->programmeCurriculum)[0]->prog_curriculum_desc;
                        }
                    ],
                    [
                        'attribute' => 'option_desc',
                        'label' => 'Option Description',
                    ],
                    'option_status',
                    'progress_type',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-prog-curr-option/update','option_id' => $model->option_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>
