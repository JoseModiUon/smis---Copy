<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnit */

$this->title = 'Create Organisation Unit';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-create">
    <div class="cohort-update">
        <div class="card" >
            <div class="card-body">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
