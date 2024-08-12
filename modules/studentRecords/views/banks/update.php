<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Banks $model */

$this->title = 'Update Banks: ' . $model->bank_id;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bank_id, 'url' => ['view', 'bank_id' => $model->bank_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
