<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgrammeCurriculum */

$this->title = 'Update Programme Curriculum: ' . $model->prog_curriculum_id;
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-programme-curriculum-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
