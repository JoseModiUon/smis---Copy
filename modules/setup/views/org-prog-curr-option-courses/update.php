<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrOptionCourses */

$this->title = 'Update Program Curriculum Option Courses: ' . $model->option_name;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Program Curriculum Option Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-option-courses-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
