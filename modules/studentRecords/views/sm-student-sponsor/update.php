<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmStudentSponsor $model */

$this->title = 'Update Student Sponsor: ' . $model->sponsor_name;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sm-student-sponsor-update">


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