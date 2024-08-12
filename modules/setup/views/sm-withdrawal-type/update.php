<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalType $model */

$this->title = 'Update Withdrawal Type: ' . $model->withdrawal_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Withdrawal Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->withdrawal_type_id, 'url' => ['view', 'withdrawal_type_id' => $model->withdrawal_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-withdrawal-type-update">
 <div class="card">
        <div class="card-body">
            
    <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
