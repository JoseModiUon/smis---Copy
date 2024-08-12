<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\examManagement\models\ProgCurrLevelRequirement $model */

$this->title = 'Create Programme Level Requirement';
$this->params['breadcrumbs'][] = ['label' => 'Create Programme Level Requirement', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prog-curr-level-requirement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
