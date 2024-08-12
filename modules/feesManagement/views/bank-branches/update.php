<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankBranches $model */

$this->title = 'Update Bank Branch: ' . $model->branch_code;
$this->params['breadcrumbs'][] = ['label' => 'Bank Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->branch_code, 'url' => ['view', 'branch_id' => $model->branch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-branches-update">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>