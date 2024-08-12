<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExMarksheet $model */

$this->title = 'Create Ex Marksheet';
$this->params['breadcrumbs'][] = ['label' => 'Ex Marksheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-marksheet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
