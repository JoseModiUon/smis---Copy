<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExMode $model */

$this->title = 'Create Exam Mode';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Exam Mode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-mode-create">
  <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

		</div>
	</div>
</div>
