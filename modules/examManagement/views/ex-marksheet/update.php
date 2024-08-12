<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExMarksheet $model */

$this->title = 'Update Ex Marksheet: ' . $model->marksheet_id;
$this->params['breadcrumbs'][] = ['label' => 'Ex Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->marksheet_id, 'url' => ['view', 'marksheet_id' => $model->marksheet_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ex-marksheet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
