<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgLevel */

$this->title = 'Update Programme Level: ' . $model->programme_level_name;
$this->params['breadcrumbs'][] = ['label' => 'Programmme Levels', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->programme_level_id, 'url' => ['view', 'programme_level_id' => $model->programme_level_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-level-update">
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
