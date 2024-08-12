<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohortSession */

$this->title = 'Update Cohort Session: ' . $model->cohort_session_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Cohort Sessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-cohort-session-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
    </div>

</div>
