<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\Banks $model */

$this->title = 'Update Banks: ' . $model->bank_name;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->bank_name, 'url' => ['view', 'brank_id' => $model->brank_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banks-update">

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