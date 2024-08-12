<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgRooms $model */

$this->title = 'Update Room: ' . $model->room_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->room_id, 'url' => ['view', 'room_id' => $model->room_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-rooms-update">

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