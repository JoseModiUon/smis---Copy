<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgType */

$this->title = 'Create Programme Type';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-type-create">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
