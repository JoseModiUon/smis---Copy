<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Create Level Requirements';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/examinationManagement']];
$this->params['breadcrumbs'][] = ['label' => 'Level Requirements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-course-prerequisite-create">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            
        </div>
    </div>

</div>
