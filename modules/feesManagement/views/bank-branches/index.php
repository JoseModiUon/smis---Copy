<?php

use app\modules\studentRecords\models\BankBranches;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankBranchesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bank Branches';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-branches-index">

    <div class="card">
        <div class="card-body">

            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create New Bank Branch',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']
                )
                ?>
            </div>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'branch_code',

                    'branch_name',
                    [
                        'label' => 'Bank Name',
                        'attribute' => 'bank_code',
                        'value' => function($model) {
                            return $model['bank']['bank_name'];
                        }
                    ],
                    // 'branch_id',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Update ',
                                    [
                                        '/fees-management/bank-branches/update',
                                        'branch_id' => $model->branch_id
                                    ],
                                    ['class' => ' bi btn-link bi-pencil-square text-primary']
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>


</div>