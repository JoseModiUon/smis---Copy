<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */

$this->title = 'Name Change Approval';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Name Change Requests', 'url' => ['sm-name-change/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-approval-create">
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
