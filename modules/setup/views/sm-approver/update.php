<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmApprover $model */

$this->title = 'Update Approver: ' . $model->approver;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Approvers', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->approver_id, 'url' => ['view', 'approver_id' => $model->approver_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-approver-update">
 <div class="card">
        <div class="card-body">
            
  <h3 class="card-title mb-3">
    <?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
