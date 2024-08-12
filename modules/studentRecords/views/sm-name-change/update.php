<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChange */

$this->title = 'Update Sm Name Change: ' . $model->name_change_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Name Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_change_id, 'url' => ['view', 'name_change_id' => $model->name_change_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-name-change-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
