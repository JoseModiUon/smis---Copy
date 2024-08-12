<?php

use app\modules\studentRecords\models\StudentSemSessionProgress;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\StudentSemSessionProgress $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Student Sem Session Progresses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-sem-session-progress-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Student Sem Session Progress'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'student_semester_session_id',
            'registration_date',
            'academic_progress_id',
            'sem_progress_number',
            'billable',
            //'rep_status_id',
            //'prom_status_id',
            //'reporting_snyc_status:boolean',
            //'semester_progress',
            //'promotion_status',
            //'prog_curriculum_semester_id',
            //'userid',
            //'ip_address',
            //'user_action',
            //'last_update',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, StudentSemSessionProgress $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'student_semester_session_id' => $model->student_semester_session_id]);
                 }
            ],
        ],
    ]); ?>


</div>
