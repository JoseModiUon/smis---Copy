<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgStudyCentreGroup */

$this->title = 'Update Study Centre Group' ;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Study Centre Groups', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->study_centre_group_id, 'url' => ['view', 'study_centre_group_id' => $model->study_centre_group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-study-centre-group-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
