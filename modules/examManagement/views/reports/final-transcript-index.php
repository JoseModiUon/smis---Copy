<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgRooms;
use app\models\OrgDays;
use app\models\OrgProgCurrSemester;
use app\models\OrgProgCurrSemesterGroup;
use app\models\OrgProgLevel;
use app\models\OrgSemesterCode;
use app\models\CrProgCurrTimetable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Academic Transcript';

$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
// $this->params['breadcrumbs'][] = ['label' => 'Academic Transcript', 'url' => ['/exam-management/reports/final-transcript']];
$this->params['breadcrumbs'][] = $this->title;

$additionalParams = Yii::$app->request->get();


?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-12">
                    <?php echo  $this->render('_search_final_transcript', ['model' => $searchModel]) ?>

                </div>
            </div>
            <hr>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'names',
                        'label' => 'Names',
                        'value' => function ($model) {
                            return $model['surname'] . ' ' . $model['other_names'];
                        },

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
                                return  Html::a(' View Transcript ', [
                                    '/exam-management/reports/view-final-transcript',
                                    'timetable_id' => $model['timetable_id'],
                                    'reg_no' => $model['student_number'],
                                    'student_id' => $model['student_id'],
                                ], ['class' => ' bi bi-eye-fill btn btn-outline-primary btn-sm']);
                            },
                        ],
                        'hAlign' => 'center',

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>