<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\StudentSemSessionProgress $model */
$request = Yii::$app->request;
$regno = $request->get('registration_number');
$name = $request->get('full_names');
$this->title = Yii::t('app', '{name}:{regno}', [
    'name' => $name,
    'regno' => $regno
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Sem Session Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student_semester_session_id, 'url' => ['view', 'student_semester_session_id' => $model->student_semester_session_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="student-sem-session-progress-update">

    <div class="row justify-content-center">

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">
                        <h5><?= $name ?></h5>
                        <h5><?= $regno ?></h5>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>

        </div>
    </div>
</div>