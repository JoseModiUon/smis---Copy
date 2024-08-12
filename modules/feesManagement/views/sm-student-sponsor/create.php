<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmStudentSponsor $model */

$this->title = 'Create Student Sponsor';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-student-sponsor-create">

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>