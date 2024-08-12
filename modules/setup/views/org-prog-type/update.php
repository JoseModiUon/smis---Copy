<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgType */

$this->title = 'Update Programme Type: ' . $model->prog_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->prog_type_id, 'url' => ['view', 'prog_type_id' => $model->prog_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-type-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
