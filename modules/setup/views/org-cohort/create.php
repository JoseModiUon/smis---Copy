<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCohort */

$this->title = 'Create Cohort';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Cohorts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-cohort-create">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
    </div>
</div>
