<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */

$this->title = 'Withdrawal Approval';
$this->params['breadcrumbs'][] = ['label' => 'Withdrawal Request', 'url' => ['sm-withdrawal-request/view','withdrawal_request_id' => Yii::$app->request->get('withdrawal_request_id')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-approval-create">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>
