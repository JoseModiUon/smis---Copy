<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomFloors $model */

$this->title = 'Update Room Floor: ' . $model->floor_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Org Room Floors', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->floor_id, 'url' => ['view', 'floor_id' => $model->floor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-room-floors-update">
  <div class="card">
    <div class="card-body">


      <div class="d-flex flex-column">
        <h3 class="card-title mb-3">
          <?= Html::encode($this->title) ?></h3>
      </div>




      <?= $this->render('_form', [
        'model' => $model,
      ]) ?>

    </div>
  </div>
</div>