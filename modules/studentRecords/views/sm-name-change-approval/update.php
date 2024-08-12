<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */

$this->title = 'Update Name Change Approval: ' . $model->name_change_approval_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Name Change Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_change_approval_id, 'url' => ['view', 'name_change_approval_id' => $model->name_change_approval_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-name-change-approval-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
