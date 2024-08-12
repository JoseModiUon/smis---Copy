<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgAcademicSessionSemesterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Academic Session Semesters';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-academic-session-semester-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Academic Session Semester',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>

            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    [
                        'attribute' => 'acadSession',
                        'label' => ' Academic Session',
                        'value' => 'acadSession.acad_session_name'
                    ],
                    [
                        'attribute' => 'semesterCode',
                        'label' => ' Semester',
                        'value' => 'semesterCode.semster_name'
                    ],
                    [
                        'attribute' => 'acad_session_semester_desc',
                        'label' => ' Academic Session Description',
                        // 'value' => 'acadSession.acad_session_desc'
                    ],
                    // 'acad_session_semester_desc',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-academic-session-semester/update','acad_session_semester_id' => $model->acad_session_semester_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
                ]); 
            ?>
        </div>
    </div>
</div>
