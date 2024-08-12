<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohort */

$this->title = 'Update Cohort: ' . $model->cohort_desc;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Org Cohorts', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-cohort-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
    </div>
</div>
