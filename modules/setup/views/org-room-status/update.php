<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomStatus $model */

$this->title = 'Update Room Status: ' . $model->room_status_desc;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Room Status', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->room_status_id, 'url' => ['view', 'room_status_id' => $model->room_status_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-room-status-update">

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