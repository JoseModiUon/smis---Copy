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


$this->title = 'Marks Entry';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;

$additionalParams = Yii::$app->request->get('ClassListSearch');


?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-12">
                    <?php echo  $this->render('_search_create_marks', ['model' => $searchModel]) ?>

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
                        'attribute' => 'Summary Header',
                        'width' => '310px',
                        'value' => function ($model) {
                            if (!empty($model)) {
                                return $model['academic_level_name'] . ' - ' . $model['semster_name'];
                            }
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        // 'filter' => ArrayHelper::map(Suppliers::find()->orderBy('company_name')->asArray()->all(), 'id', 'company_name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        // 'filterInputOptions' => ['placeholder' => 'Any supplier'],
                        'group' => true,  // enable grouping,
                        'groupedRow' => true,                    // move grouped column to a single grouped row
                        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
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
                        'template' => '{update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap;',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) use ($additionalParams) {
                                return  Html::a('View/ Post Marks', [
                                    '/courseRegistration/reports/view-marks-entry-list',
                                    'timetable_id' => $model['timetable_id'],
                                    'course_id' => $model['course_id'],
                                    ...$additionalParams,
                                ], ['class' => ' bi bi-eye-fill btn btn-outline-success btn-sm']);
                            },
                        ],
                        'hAlign' => 'center',

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>