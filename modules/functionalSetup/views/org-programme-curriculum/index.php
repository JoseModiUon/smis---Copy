<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgrammeCurriculumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculums';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-programme-curriculum-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Programme Curriculum',
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

                        // 'prog_curriculum_id',
                        [
                            'attribute' => 'prog',
                            'label' => 'Programme',
                            'value' => 'prog.prog_short_name'
                        ],
                        //'prog_curriculum_desc',
                        [
                            'attribute' => 'prog_curriculum_desc',
                            'label' => 'Description',
                            'value' => 'prog_curriculum_desc'
                        ],
                        'duration',
                        'pass_mark',
                        [
                            'attribute' => 'gradingSystem',
                            'label' => 'Grading System',
                            'value' => 'gradingSystem.grading_system_name'
                        ],
                        'annual_semesters',
//                        'max_units_per_semester',
                        [
                            'attribute' => 'max_units_per_semester',
                            'label' => 'Maximum Units per Semester',
                            'value' => 'max_units_per_semester'
                            
                        ],
                        'average_type',
                        'award_rounding',
                       // 'start_date',
                        [
                            'attribute' => 'start_date',
                            'value' => function ($model) {
                                return strtoupper(Yii::$app->formatter->asDate($model->start_date, 'php:d-M-yy'));
                            },
                        ],
                       // 'end_date',
                        [
                            'attribute' => 'end_date',
                            'value' => function ($model) {
                                if(isset($model->end_date)):
                                return strtoupper(Yii::$app->formatter->asDate($model->end_date, 'php:d-M-yy'));
                                endif;
                            },
                        ],
                       // 'prog_cur_prefix',
                        [
                            'attribute' => 'prog_cur_prefix',
                            'label' => 'Prefix',
                            'value' => 'prog_cur_prefix'
                        ],
                      //  'date_created',
                        [
                            'attribute' => 'date_created',
                            'value' => function ($model) {
                                if(isset($model->date_created)):
                                    return strtoupper(Yii::$app->formatter->asDate($model->date_created, 'php:d-M-yy'));
                                endif;
                            },
                        ],
//                        'grading_system_id',
                        'status',
                        //'approval_date',
                        [
                            'attribute' => 'approval_date',
                            'value' => function ($model) {
                                if(isset($model->approval_date)):
                                    return strtoupper(Yii::$app->formatter->asDate($model->approval_date, 'php:d-M-yy'));
                                endif;
                            },
                        ],


                        
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{update} ',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return  Html::a(' Update', ['/functionalSetup/org-programme-curriculum/update','prog_curriculum_id' => $model->prog_curriculum_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                                },
                            ]

                        ],
                    ],
                ]); 
            ?>
        </div>
    </div>



</div>
