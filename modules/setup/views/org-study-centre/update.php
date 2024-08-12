<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgStudyCentre */

$this->title = 'Update Study Centre: ' . $model->study_centre_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Study Centres', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->study_centre_id, 'url' => ['view', 'study_centre_id' => $model->study_centre_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-study-centre-update">
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
