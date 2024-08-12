<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnit */

$this->title = 'Update Organisation Unit: ' . $model->unit_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Units', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->unit_id, 'url' => ['view', 'unit_id' => $model->unit_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-unit-update">

        <div class="card" >
            <div class="card-body">
                <h3 class="card-title mb-3">
   <?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
