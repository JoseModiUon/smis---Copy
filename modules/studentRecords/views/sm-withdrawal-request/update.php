<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */
$student = $model->getStudent()->one();
$this->title = 'Withdrawal Request: ' .  $student->getFullNames() . ' '.$student->student_number;
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->withdrawal_request_id, 'url' => ['view', 'withdrawal_request_id' => $model->withdrawal_request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-withdrawal-request-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
