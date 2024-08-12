<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentId */

$this->title = 'Report ID: ' . $model->barcode . '  as lost';
$this->params['breadcrumbs'][] = ['label' => 'Student Id', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Report lost id';
?>
<<<<<<< HEAD
<<<<<<< HEAD
<div class="student-id-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update-id-form', [
        'model' => $model,
    ]) ?>

</div>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
<div class="student-id-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update-id-form', [
        'model' => $model,
    ]) ?>

</div>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
