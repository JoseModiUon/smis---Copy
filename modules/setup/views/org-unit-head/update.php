<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHead */

$this->title = 'Update Organisation Unit Head: ' . $model->unit_head_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Unit Heads', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->unit_head_id, 'url' => ['view', 'unit_head_id' => $model->unit_head_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-unit-head-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
