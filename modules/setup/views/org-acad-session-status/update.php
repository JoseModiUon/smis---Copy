<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcadSessionStatus */

$this->title = 'Update Academic Session Status: ' . $model->acad_session_status_id;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Academic Session Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-acad-session-status-update">
    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
