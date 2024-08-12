<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-02-15 11:06:08 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-19 10:11:33
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
    'Analysis Per Academic Session' => Url::to(['/courseRegistration/prog-student-analysis/']),
    $title => null
]);
?>

<div class="section">
    <div class="container">
        <?php

        $programmeName = [
            'label' => 'Programme Name',
            'attribute' => 'prog_curriculum_desc',
            'vAlign' => 'middle',

            'value' => function ($model) {
                return $model['prog_curriculum_desc'];
            }
        ];

        $teachingCount = [
            'label' => 'Teaching',
            'value' => function ($model) use ($teachingCount) {
                foreach ($teachingCount as $count) {

                    if ($model['prog_curriculum_id'] === $count['prog_curriculum_id']) {
                        return $count['student_count'];
                    }
                }
                return 0;
            }
        ];
        $supplementaryCount = [
            'label' => 'Supplementary',
            'value' => function ($model) use ($supplementaryCount) {
                foreach ($supplementaryCount as $count) {

                    if ($model['prog_curriculum_id'] === $count['prog_curriculum_id']) {
                        return $count['student_count'];
                    }
                }
                return 0;
            }
        ];

        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            $programmeName,
            $teachingCount,
            $supplementaryCount
        ];

        $toolbar = [
            '{export}',
            '{toggleData}',
        ];

        try {
            echo GridView::widget([
                'id' => 'register-for-courses-grid',
                'dataProvider' => $dataProvider,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'onclick' => 'location.href="' . Yii::$app->urlManager->createUrl(['courseRegistration/prog-student-analysis/course-analysis', 'prog_curriculum_id' => $model['prog_curriculum_id'], 'acad_session_id' => $model['acad_session_id']]) . '";',
                        'style' => "cursor: pointer",
                    ];
                },
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
                    'heading' => '<h5 class="text-center">Course Registration Analysis Report - Per Programme</h5>'
                ],
                'persistResize' => false,
                'itemLabelSingle' => 'programme',
                'itemLabelPlural' => 'programmes',
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