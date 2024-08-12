<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgSemesterType $model */

$this->title = 'Update Org Semester Type: ' . $model->sem_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Org Semester Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sem_type_id, 'url' => ['view', 'sem_type_id' => $model->sem_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-semester-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
