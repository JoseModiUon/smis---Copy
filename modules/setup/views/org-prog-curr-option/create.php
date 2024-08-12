<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOption */

$this->title = 'Create Programme Curriculum Option';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-option-create">
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
