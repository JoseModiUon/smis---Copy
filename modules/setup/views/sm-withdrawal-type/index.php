<?php

use app\models\SmWithdrawalType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Withdrawal Types';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-type-index">
<div class="card">
        <div class="card-body">
  

    
            <div class="d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-plus-lg"></i> Create Withdrawal Type', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>
	  <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'withdrawal_type_id',
            'withdrawal_type_name',
         
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/sm-withdrawal-type/update','withdrawal_type_id' => $model->withdrawal_type_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],
        ],
    ]); ?>


</div>
</div>
</div>
