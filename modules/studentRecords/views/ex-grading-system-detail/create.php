<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystemDetail $model */


$gsid=  Yii::$app->session->get('grading_system_id');
$this->title = 'Create Grading System Detail';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Grading System Details', 'url' => ['index', 'grading_system_id' => $gsid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ex-grading-system-detail-create">
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