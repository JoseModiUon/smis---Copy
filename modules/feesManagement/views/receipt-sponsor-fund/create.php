<?php

use app\models\SmStudentSponsor;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ReceiptSponsorFund $model */

$sponsor_id = Yii::$app->session->get('sponsor_id');
$sponsor_name = SmStudentSponsor::findOne($sponsor_id)->sponsor_name;
$this->title = 'Receipt Sponsor Fund';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Funds', 'url' => ['index', 'sponsor_id' => $sponsor_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-sponsor-fund-create">

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title">
                    <?= Html::encode($this->title) ?>
                    <div class="mt-2"><?= $sponsor_name ?></div>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>