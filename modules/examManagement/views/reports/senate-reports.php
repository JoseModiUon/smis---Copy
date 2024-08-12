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
use app\models\OrgAcademicSession;
use app\models\OrgProgrammeCurriculum;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Senate Reports';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$list = 'Student List';

if (isset(Yii::$app->request->queryParams['SenateReportsSearch'])) {
    $name = ucwords(Yii::$app->request->queryParams['SenateReportsSearch']['types']);
    $list = "Student ${name} List";
}
$request = Yii::$app->request->queryParams['SenateReportsSearch'] ?? [];
// $prog_curriculum_id = ucwords(Yii::$app->request->queryParams['SenateReportsSearch']['prog_curriculum_id']) ?? '';
if (isset($request['prog_curriculum_id']) && $prog_curriculum_id = $request['prog_curriculum_id']) {
    $data = OrgProgrammeCurriculum::find()
        ->select(['prog_curriculum_id', 'prog_short_name', 'prog_code'])
        ->joinWith('prog')
        ->where(['prog_curriculum_id' => $prog_curriculum_id])
        ->asArray()->one();
    $level = Yii::$app->request->queryParams['SenateReportsSearch']['academic_level'];

    $session = OrgAcademicSession::findOne(Yii::$app->request->queryParams['SenateReportsSearch']['acad_session_id'])->acad_session_name;

    // $title = '<p style="text-align: center;">NATIONAL DEFENCE UNIVERSITY OF KENYA</p>';
    $prog_name = $data['prog_short_name'];

    $centerContent = "<p style='text-align: center;'>NATIONAL DEFENCE UNIVERSITY OF KENYA</p>";
    $centerContent .= "<div style='text-align: center;'>DEGREE PROGRAMME: $prog_name </div>";
    $centerContent .= "<span style='text-align: center;'>LEVEL: ${level} </span>";
    $centerContent .= "<span style='margin-bottom:22px;'> {$list} </span>";
    $centerContent .= "<span style='text-align: center;'>ACADEMIC YEAR: $session </span>";
}


?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-12">
                    <?php echo  $this->render('_search_senate', ['model' => $searchModel]) ?>
                </div>
            </div>
            <hr>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'pjax' => true,
                'responsiveWrap' => false,
                'hover' => true,
                'striped' => false,
                'bordered' => false,
                'toolbar' => [
                    // [
                    //     'content' =>
                    //     Html::button('Add courses', [
                    //         'title' => 'Add courses',
                    //         'id' => 'add-courses-btn',
                    //         'class' => 'btn btn-success btn-spacer btn-sm',
                    //     ]),
                    //     'options' => ['class' => 'btn-group mr-2']
                    // ],
                    '{export}',
                    '{toggleData}',
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                'exportConfig' => [
                    GridView::CSV => ['label' => 'CSV'],
                    // GridView::HTML =
                    // GridView::PDF => ['label' => 'Save as PDF'],
                    GridView::PDF => \app\helpers\GridExport::exportPdf([
                        'filename' => $prog_curriculum_id  ?? '',
                        'title' => '',
                        'subject' => '',
                        'keywords' => '',
                        'contentBefore' => '-----',
                        'contentAfter' => '-----',
                        'centerContent' => $centerContent ?? '',
                        'reoderOrgName' => true,
                    ]),
                    GridView::EXCEL => ['label' => 'Excel']
                ],
                'panel' => [
                    'heading' => $list
                ],
                'persistResize' => false,
                // 'itemLabelSingle' => 'course',
                // 'itemLabelPlural' => 'courses',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'names',
                        'label' => 'Registration Number',
                        'value' => function ($model) {
                            return $model['student_number'];
                        },

                    ],
                    [
                        'attribute' => 'names',
                        'label' => 'Names',
                        'value' => function ($model) {
                            return $model['surname'] . ' ' . $model['other_names'];
                        },

                    ],
                    [
                        'attribute' => 'names',
                        'label' => 'Gender',
                        'value' => function ($model) {
                            return $model['gender'];
                        },

                    ],
                    [
                        'attribute' => 'names',
                        'label' => 'Recommendation',
                        'value' => function ($model) {
                            return $model['ext_result'];
                        },

                    ],

                    [
                        'attribute' => 'names',
                        'label' => 'Courses Taken',
                        'value' => function ($model) {
                            return $model['courses_taken'];
                        },

                    ],

                ],
            ]); ?>


        </div>
    </div>
</div>