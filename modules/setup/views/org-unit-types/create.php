<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitTypes */

$this->title = 'Create Organisation Unit Types';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Organisation Unit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
