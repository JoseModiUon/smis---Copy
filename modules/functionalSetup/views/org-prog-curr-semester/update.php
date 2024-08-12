<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgCurrSemester */

$this->title = 'Update Programme Curriculum Semester: ' . $model->prog_curriculum_semester_id;
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculum Semester', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-prog-curr-semester-update">
    <div class="card">
        <div class="card-body">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>
