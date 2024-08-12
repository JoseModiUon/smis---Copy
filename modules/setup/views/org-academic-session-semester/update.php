<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrgAcademicSessionSemester */

$this->title = 'Update Academic Session Semester: ' . $model->acad_session_semester_id;
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Academic Session Semesters', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="org-academic-session-semester-update">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
