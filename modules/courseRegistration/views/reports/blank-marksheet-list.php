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
use app\models\OrgCourses;
use app\models\OrgProgCurrUnit;
use app\models\OrgStudyGroup;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Blank Marksheet';
$this->params['breadcrumbs'][] = ['label' => 'Examination Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
$group = OrgStudyGroup::findOne(Yii::$app->request->get("study_group_id"))->study_group_name;
$timetable = CrProgCurrTimetable::findOne(Yii::$app->request->get('timetable_id'));
$course = OrgCourses::findOne(Yii::$app->request->get('course_id'));
$acad = OrgAcademicSession::findOne(Yii::$app->request->get('acad_session_id'));
$unit = OrgProgCurrUnit::find()->select(['org_unit.*'])
    ->joinWith(['orgUnitHistory' => function (ActiveQuery $or) {
        $or->joinWith(['orgUnit']);
    }])
    ->where([
        'prog_curriculum_id' => Yii::$app->request->get('prog_curriculum_id')
    ])->asArray()->one();

$date  =  Yii::$app->formatter->asDate($acad->start_date, 'd-MMM-yy') . ' - ' . Yii::$app->formatter->asDate($acad->end_date, 'd-MMM-yy');
$courseName = $course->course_name;
$courseCode = $course->course_code;
$titleName = $courseCode . " " . strtoupper($courseName);
$academicYear =  $acad->acad_session_name;
$deptName = $unit['unit_name'];

$title = $courseName . ' (' . $courseCode . ')BLANK MARKSHEET';
$fileName = $courseCode . '_examlist';
$centerContent = 'BLANK MARKSHEET';

$title = $courseName . ' (' . $courseCode . ') BLANK MARKSHEET';
$fileName = $courseCode . '_blankmarksheet';
$centerContent =  'INDIVIDUAL MARKSHEET';

$contentBefore = '<p><span  style="color:#333333; font-weight: bold;">Department:</span> ' . $deptName . '</p>';
$contentBefore .= '<p><span  style="color:#333333; font-weight: bold;">Title Of Paper:</span>' . $titleName . '</p>';
$contentBefore .= '<p>
<span><span style="font-weight: bold;">Academic Year:</span> ' . $academicYear . '</span> 
<span><span style="font-weight: bold;">Group:</span> ' . $group . '</span>
<span><span style="font-weight: bold;">Semester Dates:</span>' . $date . ' </span> 

</p>';
$contentAfter = '<div style="position:relative">

<div style="display:inline-block;margin-top:630px">
    <div>
        <div style="text-align:left">
            <span style="font-style:italic">Signed By:..................................</span><br/>
            <span style="padding-top:-20px;font-style:italic">(Internal Examiner)</span>
        <div>
        <div style="text-align:right;padding-top:-20px;">Date:............../.............../..........</div>
    </div>
    <div>
    <div style="text-align:left;margin-top:20px">
        <span style="font-style:italic">Signed By:..................................</span><br/>
        <span style="padding-top:-20px;font-style:italic">(External Examiner)</span>
    <div>
    <div style="text-align:right;padding-top:-20px;">Date:............../.............../..........</div>
    </div>
</div>


</div>';

?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-title">
            <h3 style="margin-left:20px" class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

        </div>
        <div class="card-body">
            <div class="text-justify">
                <p class="mb-2"><span style="font-weight: bold;">Department:</span> <?= $deptName ?></p>
                <p><span style="font-weight: bold;">Title Of Paper:</span> <?= $titleName ?></p>

                <p>
                    <span style="font-weight: bold;">Academic Year:</span> <?= $academicYear ?>
                    <span style="font-weight: bold;">Group:</span> <?= $group ?>
                    <span style="font-weight: bold;">Semester Dates:</span> <?= $date ?>
                </p>

            </div>

            <?php


            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportContainer' => ['class' => 'btn-group mr-2 me-2'],
                'exportConfig' => [
                    GridView::CSV => ['label' => 'CSV'],
                    // GridView::HTML =
                    // GridView::PDF => ['label' => 'Save as PDF'],
                    GridView::PDF => \app\helpers\GridExportMarksheet::exportPdf([
                        'filename' => $fileName,
                        'title' => $title,
                        'subject' => 'class list',
                        'keywords' => 'class list',
                        'contentBefore' => $contentBefore,
                        'contentAfter' => $contentAfter,
                        'centerContent' => $centerContent
                    ]),
                    GridView::EXCEL => ['label' => 'Excel']
                ],
                'toolbar' => [
                    '{toggleData}',
                    '{export}',

                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //. No. Surname Other Names Student Email Student Mobile
                    [
                        'attribute' => 'Reg',
                        'label' => 'Reg. No.',
                        'value' => function ($model) {
                            return $model['student_number'];
                        }
                    ],
                    [
                        'attribute' => 'student names',
                        'value' => function ($model) {
                            return $model['surname'] . ' ' . $model['other_names'];;
                        }
                    ],
                    [
                        'attribute' => 'exam type',
                        'value' => function ($model) {
                            return 'FIRST ATTEMPT';
                        }
                    ],

                    [
                        'attribute' => 'course work',
                        'label' => 'Course Work',
                        'value' => function ($model) {
                            return ' ';
                        }
                    ],

                    [
                        'attribute' => 'final exam',
                        'label' => 'Final Exam',
                        'value' => function ($model) {
                            return ' ';
                        }
                    ],
                    [
                        'attribute' => 'remarks',
                        'value' => function ($model) {
                            return ' ';
                        }
                    ],
                ],
            ]);
            ?>


        </div>
    </div>
</div>