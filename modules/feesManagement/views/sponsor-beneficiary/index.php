<?php

use app\modules\feesManagement\models\SponsorBeneficiary;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\SponsorBeneficiary $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sponsor Beneficiaries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-beneficiary-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sponsor Beneficiary', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sponsor_beneficiary_id',
            'receipt_sponsor_fund_id',
            'reg_no',
            'name',
            'trans_type',
            //'transfer_status',
            //'amount',
            //'post_date',
            //'user_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SponsorBeneficiary $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]);
                 }
            ],
        ],
    ]); ?>


</div>
