<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgRoomType $model */

$this->title = 'Create Room Type';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Room Types', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-room-type-create">

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