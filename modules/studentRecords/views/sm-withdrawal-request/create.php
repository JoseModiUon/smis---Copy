<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */

$this->title = 'Create Sm Withdrawal Request';
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
