<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\StudentSemSessionProgress $model */


$request = Yii::$app->request;


$registration_number = $request->get('registration_number');
$fullName = $request->get('fullName');

// equivalent to: $id = isset
$this->title = Yii::t('app', 'Create Student Sem Session Progress');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fees Management'), 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Semester Session Progress'), 'url' => ['/student-records/student-semester-progress', 'StudentSemesterProgressSearch[registration_number]' => $registration_number]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-sem-session-progress-create">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h1>
            <div class="d-flex justify-content-between">
                <h3><?=$registration_number?></h3>
                <h4><?=$fullName?></h4>
            </div>
            <?= $this->render('_create', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>