<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHistory */

$this->title = 'Update Organisation Unit History';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Unit History', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->org_unit_history_id, 'url' => ['view', 'org_unit_history_id' => $model->org_unit_history_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-unit-history-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
