<?php

use app\models\SmStudentSponsor;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ReceiptSponsorFund $model */

$sponsor = SmStudentSponsor::findOne($model->sponsor_id);
$this->title = 'Update Receipt Sponsor Fund: ' . $sponsor->sponsor_name;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Funds', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="receipt-sponsor-fund-update">
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