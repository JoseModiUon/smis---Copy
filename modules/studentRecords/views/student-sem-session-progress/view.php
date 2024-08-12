<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\StudentSemSessionProgress $model */

$this->title = $model->student_semester_session_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Sem Session Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-sem-session-progress-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'student_semester_session_id' => $model->student_semester_session_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'student_semester_session_id' => $model->student_semester_session_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'student_semester_session_id',
            'registration_date',
            'academic_progress_id',
            'sem_progress_number',
            'billable',
            'rep_status_id',
            'prom_status_id',
            'reporting_snyc_status:boolean',
            'semester_progress',
            'promotion_status',
            'prog_curriculum_semester_id',
            'userid',
            'ip_address',
            'user_action',
            'last_update',
        ],
    ]) ?>

</div>
