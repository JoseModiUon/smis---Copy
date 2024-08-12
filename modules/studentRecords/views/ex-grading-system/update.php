<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystem $model */

$this->title = 'Update Grading System: ' . $model->grading_system_desc;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];

$this->params['breadcrumbs'][] = ['label' => 'Grading Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ex-grading-system-update">

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