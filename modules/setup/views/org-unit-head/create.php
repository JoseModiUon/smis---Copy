<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHead */

$this->title = 'Create Organisation Unit Head';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Unit Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-head-create">
    <div class="cohort-update">
        <div class="card" >
            <div class="card-body">
                <h3 class="card-title mb-3">
   <?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
