<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmExamResult $model */

$this->title = 'Create Sm Exam Result';
$this->params['breadcrumbs'][] = ['label' => 'Sm Exam Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-exam-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
