<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrSemesterGroup */

$this->title = 'Update Program Curriculum Semester Group: ' . $model->prog_curriculum_sem_group_id;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Program Curriculum Semester Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-semester-group-update">

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>