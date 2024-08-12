<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgAcademicSession;
use app\models\OrgCourses;
use app\models\OrgProgCurrUnit;
use app\models\StudentProgrammeCurriculum;
use kartik\dialog\Dialog;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);





$this->title = 'Marks Entry';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <div class="text-justify">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            </div>

            <br>
            <div class="row mb-2">
                <div class="col-md-12">
                    <?php echo  $this->render('_search_student_create_marks', ['model' => $searchModel]) ?>
                </div>
            </div>
            <hr>
            <?php


            echo GridView::widget([
                'id' => 'marks-entry-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                'export' => false,
                'toolbar' => [
                    '{toggleData}',

                ],

                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //. No. Surname Other Names Student Email Student Mobile
                    [
                        'label' => 'Student Details',
                        'attribute' => 'student_number',
                        'value' => function ($model) {
                            return $model['student_number'] . ' -' . $model['surname'] . ' ' . $model['other_names'];
                        }
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap;',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a('View/ Post Marks', [
                                    '/exam-management/reports/student-marks-entry',
                                    'student_id' => $model['student_id'],
                                    'prog_curriculum_id' => $model['prog_curriculum_id'],
                                ], ['class' => ' bi bi-eye-fill btn btn-outline-success btn-sm']);
                            },
                        ],
                        'hAlign' => 'center',

                    ],
                ],
            ]);
            ?>


        </div>
    </div>
</div>