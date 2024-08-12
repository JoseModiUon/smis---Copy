<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCategory */

$this->title = 'Update Programme Category: ' . $model->prog_cat_name;
$this->params['breadcrumbs'][] = ['label' => 'Programme Categories', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->prog_cat_id, 'url' => ['view', 'prog_cat_id' => $model->prog_cat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-category-update">
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
