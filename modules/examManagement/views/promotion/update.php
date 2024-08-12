<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmExamResult $model */

$this->title = 'Update Sm Exam Result: ' . $model->exam_result_id;
$this->params['breadcrumbs'][] = ['label' => 'Sm Exam Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exam_result_id, 'url' => ['view', 'exam_result_id' => $model->exam_result_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-exam-result-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
