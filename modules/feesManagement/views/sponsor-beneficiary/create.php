<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SponsorBeneficiary $model */

$this->title = 'Create Sponsor Beneficiary';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsor', 'url' => ['/fees-management/sm-student-sponsor']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Fund', 'url' => ['/fees-management/receipt-sponsor-fund', 'sponsor_id' => Yii::$app->session->get('sponsor_id')]];
// $this->params['breadcrumbs'][] = ['label' => 'Sponsor Beneficiary', 'url' => ['/fees-management/sponsor-beneficiary/create', ['receipt_sponsor_fund_id' => 29]]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-beneficiary-create">

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>