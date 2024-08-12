<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCohortSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cohort Sessions';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-cohort-session-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Cohort Sessions',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <?= 
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'export' => false,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],

                        // 'cohort_session_id',
                        [
                            'attribute' => 'cohort_session_name',
                            'label' => 'Session Name',
                        ],
                        [
                            'attribute' => 'cohort',
                            'label' => 'Cohort',
                            'value' => 'cohort.cohort_desc'
                        ],
                        [
                            'attribute' =>'acadProgDesc',
                            'label' => 'Program Curriculum Semester',
                            'value' => function($model) {
                                $acad = $model['progCurriculumSemester']['acadSessionSemester']['acad_session_semester_desc'];
                                $prog = $model['progCurriculumSemester']['progCurriculum']['prog_curriculum_desc'];
                                return $acad. '-'. $prog;
                            }

                        ],
                        'status',

                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{update} ',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return  Html::a(' Update', ['/setup/org-cohort-session/update','cohort_session_id' => $model->cohort_session_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                },
                            ]
    
                        ],
                    ],
                ]); 
            
            ?>
        </div>
    </div>

</div>
