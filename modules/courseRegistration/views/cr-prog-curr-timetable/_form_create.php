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

$findtimetableid = function ($params) {
    $model = CrProgCurrTimetable::find()->where([
        "prog_curriculum_course_id" => $params['prog_curriculum_course_id'],
        "prog_curriculum_sem_group_id" => $params['prog_curriculum_sem_group_id']
    ])->one();
    return $model ?? false;
};

?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                    <?php echo  $this->render('_search_create', ['model' => $searchModel, 'params' => $params]) ?>

                </div>
            </div>
            <hr>
            <br>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'Summary Header',
                        'width' => '310px',
                        'value' => function ($model) {
                            if (!empty($model)) {
                                $level = OrgProgLevel::findOne($model['level_of_study'])->programme_level_name;
                                return $level . ' - ' . $model['semster_name'];
                            }
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'group' => true,
                        'groupedRow' => true,
                        'groupOddCssClass' => 'kv-grouped-row',
                        'groupEvenCssClass' => 'kv-grouped-row',
                        'format' => 'raw'
                    ],

                    [
                        'attribute' => 'course',
                        'label' => 'Course',
                        'value' => function ($model) {
                            return $model['course_code'] . ' - ' . $model['course_name'];
                        },

                    ],



                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {view}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap;',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model) use ($findtimetableid, $params) {
                                if (!$findtimetableid($model)) {

                                    return  Html::a(' Create Lecture Schedule', [
                                        '/courseRegistration/cr-prog-curr-timetable/new',
                                        'prog_curriculum_course_id' => $model['prog_curriculum_course_id'],
                                        'prog_curriculum_sem_group_id' => $model['prog_curriculum_sem_group_id'],
                                        'course_id' => $model['course_id'],
                                        'url' =>   Html::a(' Timetable has been altered Click to Publish Timetable', ['/courseRegistration/cr-programme-curr-lecture-timetable', 'params' => $params], ['class' => 'bi bi-printer btn btn-danger']),

                                    ], ['class' => ' bi bi-plus btn btn-outline-success btn-sm']);
                                }
                            },
                            'view' => function ($url, $model) use ($findtimetableid, $params) {
                                if ($findtimetableid($model)) {
                                    return Html::a(' View Schedule', [
                                        '/courseRegistration/cr-programme-curr-lecture-timetable/view',
                                        'prog_curriculum_course_id' => $model['prog_curriculum_course_id'],
                                        'prog_curriculum_sem_group_id' => $model['prog_curriculum_sem_group_id'],
                                        'course_id' => $model['course_id'],
                                        'url' =>   Html::a(' Timetable has been altered Click to Publish Timetable', ['/courseRegistration/cr-programme-curr-lecture-timetable', 'params' => $params], ['class' => ' bi bi-printer btn btn-danger']),

                                    ], ['class' => ' bi bi-eye-fill btn btn-outline-primary btn-sm']);
                                }
                            },
                        ],
                        'hAlign' => 'center',

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>