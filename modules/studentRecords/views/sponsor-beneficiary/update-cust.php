<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Beneficiaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sponsor-beneficiary-update">


    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form_cust', [
        'model' => $model,
    ]) ?>

</div>