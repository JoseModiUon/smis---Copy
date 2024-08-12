<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */

$this->title = $model->withdrawal_approval_id ;
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sm-withdrawal-approval-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'withdrawal_approval_id ' => $model->withdrawal_approval_id , 'withdrawal_request_id ' => $model->withdrawal_request_id , 'approver_id' => $model->approver_id, 'approval_status' => $model->approval_status], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'withdrawal_approval_id ' => $model->withdrawal_approval_id , 'withdrawal_request_id ' => $model->withdrawal_request_id , 'approver_id' => $model->approver_id, 'approval_status' => $model->approval_status], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'withdrawal_approval_id ',
            'withdrawal_request_id ',
            'approver_id',
            'comments :ntext',
            'approval_status',
        ],
    ]) ?>

</div>
