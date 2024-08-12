<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SponsorBeneficiary $model */

$this->title = 'Update Sponsor Beneficiary: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Beneficiaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sponsor-beneficiary-update">

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
                'disabled' => true,
            ]) ?>
        </div>
    </div>
</div>