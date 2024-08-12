<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalType $model */

$this->title = 'Create Withdrawal Type';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Withdrawal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-type-create">
 <div class="card">
        <div class="card-body">
          

    <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
