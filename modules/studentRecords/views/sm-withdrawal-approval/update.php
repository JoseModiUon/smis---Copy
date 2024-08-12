<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */

$this->title = 'Update Sm Withdrawal Approval: ' . $model->withdrawal_approval_id ;
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->withdrawal_approval_id , 'url' => ['view', 'withdrawal_approval_id ' => $model->withdrawal_approval_id , 'withdrawal_request_id ' => $model->withdrawal_request_id , 'approver_id' => $model->approver_id, 'approval_status' => $model->approval_status]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-withdrawal-approval-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
