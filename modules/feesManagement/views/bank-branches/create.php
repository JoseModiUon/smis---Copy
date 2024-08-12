<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankBranches $model */

$this->title = 'Create Bank Branch';
$this->params['breadcrumbs'][] = ['label' => 'Bank Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-branches-create">

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