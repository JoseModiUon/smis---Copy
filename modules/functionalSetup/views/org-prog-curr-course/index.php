<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrCourse;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programme Curriculum Courses';
$this->params['breadcrumbs'][] = ['label' => 'Functional Setup', 'url' => ['/functionalSetup']];
$this->params['breadcrumbs'][] = $this->title;


$session = Yii::$app->session;

if (!empty($requestData =  Yii::$app->request->get('OrgProgCurrCourseSearch'))) {
    if (array_key_exists('prog_curriculum_id', $requestData)) {
        $session->set('prog_curriculum_id', $requestData['prog_curriculum_id']);
    }
}
?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?></h3>

            <div class="d-flex justify-content-end">
                <?php //Html::a('<i class="bi bi-plus-lg"></i> Create Programme Curriculum Course', ['create'], ['class' => 'btn btn-primary'])
                ?>
            </div>
            <hr>
            <div class="row mb-2">
                <div class="col-md-6">
                    <?php echo  $this->render('_search', ['model' => $searchModel]) ?>

                </div>
            </div>
            <hr>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //            'prog_curriculum_course_id',
                    //            'prog_curriculum_id',
                    [
                        'attribute' => 'Summary Header',
                        'width' => '310px',
                        'value' => function ($model) {
                            if ($model['academicLevels']) {
                                $level = '<strong>Level of Study:</strong> ' . $model['academicLevels']['academic_level_name'];
                                $semester = '<strong>Semester:</strong> ' . $model['semesterCode']['semster_name'];
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

                    [
                        'attribute' => 'course',
                        'label' => 'Course',
                        'value' => function ($model) {
                            return $model['course']['course_code'] . ' - ' . $model['course']['course_name'];
                        },
                        'group' => true

                    ],

                    'credit_factor',
                    'credit_hours',


                    'status',

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/functionalSetup/org-prog-curr-course/update', 'prog_curriculum_course_id' => $model['prog_curriculum_course_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>


        </div>
    </div>
</div>