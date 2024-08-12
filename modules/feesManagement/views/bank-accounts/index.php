<?php

use app\modules\studentRecords\models\BankAccounts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\BankAccountsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bank Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-accounts-index">
    <div class="card">
        <div class="card-body">

            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Bank Accounts',
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

                    [
                        'label' => 'Bank Name',
                        'attribute' => 'bank_account',
                        'value' => function($model){
                            return $model->getBank()->one()->bank_name;
                        }
                    ],
                    [
                        'label' => 'Branch Name',
                        'attribute' => 'branch_code',
                        'value' => function($model){
                            return $model->getBranch()->one()->branch_name;
                        }
                    ],
                    'account_no',
                    'account_details',
                    'account_type',
                    //'min_amount',
                    //'max_amount',
                    //'currency_id',
                    //'brank_account_id',


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
                                        '/fees-management/bank-accounts/update',
                                        'brank_account_id' => $model->brank_account_id
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