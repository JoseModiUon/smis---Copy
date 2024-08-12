<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OrgSemesterType $model */

$this->title = 'Create Org Semester Type';
$this->params['breadcrumbs'][] = ['label' => 'Org Semester Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-semester-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
