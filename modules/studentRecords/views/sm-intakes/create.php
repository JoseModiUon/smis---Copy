<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmIntakes $model */

$this->title = 'Create Intakes';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Intakes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-intakes-create">

    <div class="card">
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
</div>