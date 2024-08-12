<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-15 11:06:08 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-19 10:27:05
 * @Description: file:///home/user/Documents/smis/modules/courseRegistration/views/prog-student-analysis/index.php
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use app\models\CrProgCurrTimetable;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\web\ServerErrorHttpException;

/** @var yii\web\View $this */
/** @var app\modules\courseRegistration\models\search\ProgStudentAnalysisSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<?php
$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Course Registration' => Url::to(['/courseRegistration']),
    'Analysis Per Academic Session' => Url::to(['/courseRegistration/prog-student-analysis']),
    'Analysis Per Programme Curriculum' => Url::to(['/courseRegistration/prog-student-analysis/programme-analysis', 'acad_session_id' => $id]),
    $title => null
]);
?>

<div class="section">
    <div class="container">
        <?php

        $courseID = [
            'label' => 'Course Code',
            'attribute' => 'course_code',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model['course_code'];
            }
        ];

        $courseName = [
            'label' => 'Course Name',
            'attribute' => 'course_name',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model['course_name'];
            }
        ];

        $teachingCount = [
            'label' => 'Teaching',
            'value' => function ($model) use ($teachingCount) {
                foreach ($teachingCount as $count) {

                    if ($model['course_id'] === $count['course_id']) {
                        return $count['total_students'];
                    }
                }
                return 0;
            }
        ];
        $supplementaryCount = [
            'label' => 'Supplementary',
            'value' => function ($model) use ($supplementaryCount) {
                foreach ($supplementaryCount as $count) {

                    if ($model['course_id'] === $count['course_id']) {
                        return $count['total_students'];
                    }
                }
                return 0;
            }
        ];

        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            $courseID,
            $courseName,
            $teachingCount,
            $supplementaryCount
        ];

        $toolbar = [
            '{export}',
            '{toggleData}',
        ];
        $toolbar = [
            '{export}',
            '{toggleData}',
        ];

        try {
            echo GridView::widget([
                'id' => 'course-analysis-semester-one-grid',
                'dataProvider' => $semesterOneData,

                'columns' => $gridColumns,
                'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'pjax' => true,
                'responsiveWrap' => false,
                'condensed' => true,
                'hover' => true,
                'striped' => false,
                'bordered' => false,
                'toolbar' => $toolbar,
                'export' => [
                    'fontAwesome' => true,
                    'label' => 'Export Report'
                ],
                'panel' => [
                    'heading' => '<h5 class="text-center"> Semester 1 Course Registration Analysis Report - Per Course</h5>'
                ],
                'persistResize' => false,
                'itemLabelSingle' => 'course',
                'itemLabelPlural' => 'courses',
            ]);
            echo GridView::widget([
                'id' => 'course-analysis-semester-two-grid',
                'dataProvider' => $semesterTwoData,

                'columns' => $gridColumns,
                'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                'pjax' => true,
                'responsiveWrap' => false,
                'condensed' => true,
                'hover' => true,
                'striped' => false,
                'bordered' => false,
                'toolbar' => $toolbar,
                'export' => [
                    'fontAwesome' => true,
                    'label' => 'Export Report'
                ],
                'panel' => [
                    'heading' => '<h5 class="text-center"> Semester 2 Course Registration Analysis Report - Per Course</h5>'
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