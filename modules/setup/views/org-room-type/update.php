<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomType $model */

$this->title = 'Update Room Type: ' . $model->room_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Room Types', 'url' => ['index']];

//$this->params['breadcrumbs'][] = ['label' => $model->room_type_id, 'url' => ['view', 'room_type_id' => $model->room_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-room-type-update">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>