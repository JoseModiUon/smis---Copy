<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitTypes */

$this->title = 'Update Organisation Unit Types: ' . $model->unit_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Unit Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->unit_type_id, 'url' => ['view', 'unit_type_id' => $model->unit_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-unit-types-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"> <?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
