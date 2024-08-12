<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\SponsorBeneficiary $model */

$this->title = 'Create Sponsor Beneficiary';
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Beneficiaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-beneficiary-create">

    <h3 class="card-title mb-3">
        <?= Html::encode($this->title) ?>
    </h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>