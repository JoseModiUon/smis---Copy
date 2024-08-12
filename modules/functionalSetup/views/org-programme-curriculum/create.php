<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgrammeCurriculum */

$this->title = 'Create Programme Curriculum';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-programme-curriculum-create">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
