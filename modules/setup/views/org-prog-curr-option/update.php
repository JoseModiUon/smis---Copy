<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOption */

$this->title = 'Update Programme Curriculum Option: ' . $model->option_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Options', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->option_id, 'url' => ['view', 'option_id' => $model->option_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-option-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
