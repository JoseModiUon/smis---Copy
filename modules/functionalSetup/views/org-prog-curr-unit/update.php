<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrUnit */

$this->title = 'Update Org Prog Curr Unit: ' . $model->prog_curriculum_unit_id;
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Units', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->prog_curriculum_unit_id, 'url' => ['view', 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-unit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
