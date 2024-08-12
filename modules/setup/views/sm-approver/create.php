<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmApprover $model */

$this->title = 'Create Approver';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Approvers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-approver-create">

    <div class="card">
        <div class="card-body">
              <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
