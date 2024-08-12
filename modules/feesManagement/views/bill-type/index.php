<?php

use app\modules\feesManagement\models\BillingType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\search\BillingTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Billing Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fees Management'), 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programme Curriculum'), 'url' => ['programme-curriculum/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-type-index">

  <div class="row justify-content-center">
    <div class="col-md-10">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'billing_type_id',
            [
                'attribute' => 'billing_type_desc',
                'label' => 'Billing Type'
            ]
            ,

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
                            ' Update',
                            [
                                '/fees-management/bill-type/update',
                                'billing_type_id' => $model->billing_type_id,
                            ],
                            [
                                'class' => ' bi btn-link bi bi-pencil-square text-success',
                            ],
                        );
                    },                  
                ],
            ], 
        ],
        'toolbar' => [
            ['content'=>
                Html::a('Create New Billing', ['create'], ['class' => 'btn btn-outline-primary']),
            ],
        ],
        'panel' => [
            'type' => 'default', 
            'heading' => '<i class="fa fa-list"></i> <b>'.$this->title.'</b>',
            'before' => '<em>* '.Yii::t('yii2-ajaxcrud', 'Resize Column').'</em>',                       
        ] 
    ]); ?>

    <?php Pjax::end(); ?>
    </div>
  </div>

</div>
