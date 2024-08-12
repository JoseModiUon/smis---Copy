<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PaymentMode $model */

// $this->title = 'Update Payment Mode: ' . $model->payment_mode_id;
$this->title = 'Update Payment Mode: ' . $model->mode_code;
$this->params['breadcrumbs'][] = ['label' => 'Payment Modes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mode_code, 'url' => ['view', 'payment_mode_id' => $model->payment_mode_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-mode-update">

    <!-- <h1></?= Html::encode($this->title) ?></h1>

    </?= $this->render('_form', [
        'model' => $model,
    ]) ?> -->
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

</div>
