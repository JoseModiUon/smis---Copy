<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/16/2023
 * @time: 11:53 AM
 */

/**
 * @var $this yii\web\View
 * @var string $title
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\courseRegistration\models\search\TimetablesSearch $searchModel
 * @var string[] $years
 * @var string[] $programs
 * @var array $programsCurr
 * @var string[] $academicLevels
 * @var string[] $semesters
 * @var string[] $groups
 * @var string[] $activeFilters
 * @var string $panelHeader
 * @var string $actionId
 */

use app\helpers\SmisHelper;
use app\modules\studentRegistration\models\CourseRegistration;
use kartik\grid\GridView;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$action = Url::to(['/courseRegistration/register-for-courses/timetables', 'actionId' => $actionId]);

$breadcrumbUrls = [];
if ($actionId === 'teaching') {
    $breadcrumbUrls = [
        'Course registration' => Url::to(['/courseRegistration']),
        'Teaching timetable' => null
    ];
} elseif ($actionId === 'supp') {
    $breadcrumbUrls = [
        'Course registration' => Url::to(['/courseRegistration']),
        'Supplementary timetable' => null
    ];
}
?>

<!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb($breadcrumbUrls);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <?= $this->render('filters', [
            'action' => $action,
            'years' => $years,
            'programs' => $programs,
            'programsCurr' => $programsCurr,
            'academicLevels' => $academicLevels,
            'groups' => $groups,
            'semesters' => $semesters,
            'actionId' => $actionId
        ]); ?>
        <div class="row">
            <div class="col-10 offset-1">
                <?php
                $idCol = [
                    'attribute' => 'timetable_id',
                    'label' => 'id',
                    'vAlign' => 'middle',
                ];
                $codeCol = [
                    'attribute' => 'cse.course_code',
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return $model['programmeCurriculumCourse']['course']['course_code'];
                    }
                ];
                $nameCol = [
                    'attribute' => 'cse.course_name',
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return $model['programmeCurriculumCourse']['course']['course_name'];
                    }
                ];
                $countRegisteredCol = [
                    'label' => 'REGISTERED STUDENTS',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        $timetableId = $model['timetable_id'];
                        $countRegistered = CourseRegistration::find()
                            ->where(['timetable_id' => $timetableId])->count();
                        $countStudentsInSemSession = (new Query())
                            ->from('smis.sm_student_sem_session_progress ssp')
//                        ->innerJoin('smis.org_prog_curr_semester_group pcsp', 'pcsp.prog_curriculum_sem_group_id=ssp.prog_curriculum_semester_id')
                            ->innerJoin('smis.cr_prog_curr_timetable pct', 'pct.prog_curriculum_sem_group_id=ssp.prog_curriculum_semester_id')
                            ->where(['pct.timetable_id' => $timetableId])
                            ->count();
                        return $countRegistered . '/' . $countStudentsInSemSession . ' students';
                    }
                ];
                $examTypeCol = [
                    'label' => 'TYPE',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $name = 'timetable-' . $model['timetable_id'] . '-exam-type';
                        return '
                            <select id="' . $name . '" class="exam-type" name="' . $name . '">
                                <option value=""></option>
                                <option value="FA">FIRST ATTEMPT</option>
                                <option value="RETAKE">RETAKE</option>
                                <option value="SPECIAL">SPECIAL</option>
                            </select>';
                    }
                ];

                $actionCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'ACTIONS',
                    'template' => '{students}',
                    'contentOptions' => ['style' => 'white-space:nowrap;', 'class' => 'kartik-sheet-style kv-align-middle'],
                    'buttons' => [
                        'students' => function ($url, $model) use ($actionId) {
                            return Html::a('<i class="fas fa-registered"></i> students to register',
                                Url::to([
                                    '/courseRegistration/register-for-courses/students-to-register',
                                    'actionId' => $actionId,
                                    'marksheetId' => $model['mrksheet_id']
                                ]),
                                [
                                    'title' => 'View students to register',
                                    'class' => 'btn btn-link'
                                ]
                            );
                        },
                        'class-list' => function ($url, $model) {
                            return Html::a('<i class="fas fa-list"></i> class list',
                                Url::to([
                                    '/courseRegistration/register-for-courses/class-list',
                                    'marksheetId' => $model['mrksheet_id']
                                ]),
                                [
                                    'title' => 'View registered students',
                                    'class' => 'btn btn-link'
                                ]
                            );
                        }
                    ]
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $codeCol,
                    $nameCol,
                    $countRegisteredCol,
                    $actionCol
                ];

                try {
                    echo GridView::widget([
                        'id' => 'courses-in-timetable-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => [],
                        'export' => false,
                        'panel' => [
                            'heading' => $panelHeader
                        ],
                        'persistResize' => false,
                        'itemLabelSingle' => 'course',
                        'itemLabelPlural' => 'courses',
                    ]);
                } catch (Throwable $ex) {
                    $message = $ex->getMessage();
                    if (YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
        </div>
    </div>
</section>
