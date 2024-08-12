<?php

use app\models\OrgAcademicLevels;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrgCourses;
use app\models\OrgSemesterCode;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgCoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Courses';

$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="org-courses-index">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Courses', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>

            <div class="d-flex flex-column">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?></h3>
            </div>






            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //            'course_id',
                    [
                        'attribute' => 'Summary Header',
                        'width' => '310px',
                        'value' => function ($model) {

                            if ($model['level_of_study']) {
                                $level = OrgAcademicLevels::findOne($model['level_of_study'])->academic_level_name ?? '';
                                $semester = OrgSemesterCode::findOne($model['semester'])->semster_name ?? '';
                                return $level . "&nbsp;&nbsp;&nbsp;&nbsp;" . $semester;
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
                    'course_code',
                    'course_name',
                    'academic_hours',



                    [
                        'attribute' => 'orgUnit',
                        'label' => 'Unit',
                        'value' => 'orgUnit.unit_name',
                    ],

                    //            [
                    //                'attribute' => 'prog_type_name',
                    //                'label' => 'Programme Type Name',
                    //                'value' => 'progCat.prog_cat_name',
                    //            ],
                    'billing_factor',
                    //            'category_id',
                    [
                        'attribute' => 'category',
                        'label' => 'Course Category',
                        'value' => 'category.category_name',
                    ],
                    'status',
                    //            [
                    //                'class' => ActionColumn::className(),
                    //                'urlCreator' => function ($action, OrgCourses $model, $key, $index, $column) {
                    //                    return Url::toRoute([$action, 'course_id' => $model->course_id]);
                    //                 }
                    //            ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/setup/org-courses/update', 'course_id' => $model->course_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],

                ],
            ]); ?>


        </div>
    </div>
</div>