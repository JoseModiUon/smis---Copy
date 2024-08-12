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
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Class List';
$this->params['breadcrumbs'][] = ['label' => 'Course Registration', 'url' => ['/courseRegistration']];
$this->params['breadcrumbs'][] = $this->title;
$course = OrgCourses::findOne(Yii::$app->request->get('course_id'));
$acad = OrgAcademicSession::findOne(Yii::$app->request->get('acad_session_id'));
$unit = OrgProgCurrUnit::find()->select(['org_unit.*'])
    ->joinWith(['orgUnitHistory' => function (ActiveQuery $or) {
        $or->joinWith(['orgUnit']);
    }])
    ->where([
        'prog_curriculum_id' => Yii::$app->request->get('prog_curriculum_id')
    ])->asArray()->one();

$courseName = $course->course_name;
$courseCode = $course->course_code;
$academicYear =  $acad->acad_session_desc;
$deptName = $unit['unit_name'];

$title = $courseName . ' (' . $courseCode . ') CLASS REGISTER';
$fileName = $courseCode . '_classlist';
$centerContent = 'CLASS REGISTER';

$title = $courseName . ' (' . $courseCode . ') CLASS REGISTER';
$fileName = $courseCode . '_classlist';
$centerContent = 'CLASS REGISTER';

$contentBefore = '<p style="color:#333333; font-weight: bold;">Academic year: ' . $academicYear . '</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Course: ' . $courseName . ' (' . $courseCode . ')</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Department: ' . $deptName . '</p>';
$contentBefore .= '<p style="color:#333333; font-weight: bold;">Date: ........................ Time: ........................</p>';
$contentAfter = '<div style="position: absolute; bottom: 80px;">
<span>Lecture:</span> ..........................
<span>Sign:</span> ............................
<span>Date:</span> ............................
<hr>
<div style="text-align:center"><span style="font-weight:bold">For Official Use</span> </div>
<div>
    <span>Sheet:</span> ..........................
    <span>of:</span> ............................
</div>

<div>
    <span> For Last Sheet, provide summary as follows : Total No in Session</span> .......................................................               
</div>
</div>';

?>
<div class="org-prog-curr-course-index">
    <div class="card">
        <div class="card-body">
            <div class="text-justify">
                <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                <p><span style="font-weight: bold;">Academic Year:</span> <?= $academicYear ?></p>
                <p><span style="font-weight: bold;">Course:</span> <?= "$courseName({$courseCode})" ?></p>
                <p class="mb-2"><span style="font-weight: bold;">Department:</span> <?= $deptName ?></p>

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
                    GridView::PDF => \app\helpers\GridExport::exportPdf([
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
                        'attribute' => 'surname',
                        'value' => function ($model) {
                            return $model['surname'];
                        }
                    ],
                    [
                        'attribute' => 'other_names',
                        'value' => function ($model) {
                            return $model['other_names'];
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label' => 'Student Email',
                        'value' => function ($model) {
                            return $model['primary_email'] ?? $model['alternative_email'];
                        }
                    ],
                    [
                        'attribute' => 'mobile',
                        'label' => 'Student Mobile',
                        'value' => function ($model) {
                            return $model['primary_phone_no'] ?? $model['alternative_phone_no'];
                        }
                    ],

                    [
                        'attribute' => 'signature',
                        'label' => 'Student Signature',
                        'value' => function ($model) {
                            return '';
                        }
                    ],
                ],
            ]);
?>


        </div>
    </div>
</div>