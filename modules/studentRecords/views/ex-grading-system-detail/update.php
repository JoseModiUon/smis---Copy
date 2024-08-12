<?php

use app\models\ExGradingSystem;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExGradingSystemDetail $model */

$gsid=  Yii::$app->session->get('grading_system_id');
$ExGradingSystem = ExGradingSystem::findOne($gsid);
$this->title = 'Update Grading System Detail: ' . $ExGradingSystem->grading_system_desc;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Grading System Details', 'url' => ['index', 'grading_system_id' => $gsid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ex-grading-system-detail-update">
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