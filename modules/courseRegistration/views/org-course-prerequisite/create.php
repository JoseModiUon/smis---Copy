<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCoursePrerequisite */

$this->title = 'Create Course Prerequisite';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = ['label' => 'Course Prerequisites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-course-prerequisite-create">

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