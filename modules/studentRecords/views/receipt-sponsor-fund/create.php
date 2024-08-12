<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ReceiptSponsorFund $model */
$request = Yii::$app->request;
$get = $request->get('sponsor_id');
$this->title = 'Create Receipt Sponsor Fund';
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsor', 'url' => ['/fees-management/sm-student-sponsor']];
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Funds', 'url' => ['index', 'sponsor_id' => $get]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-sponsor-fund-create">

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