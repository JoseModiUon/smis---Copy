<?php
/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-01-29 21:05:06 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-02-15 11:23:11
 * @Description: file:///home/wahomez/Github/smis/modules/studentRecords/views/admission-report/index.php
 */


use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

?>
<?php
$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Admissions' => Url::to(['/admissions']),
    $title => null
]);
?>

<div class="section">
    <div class="container">
        <?php
        $progCol = [
            'label' => 'Programme',
            'value' => function ($model) {
                return $model['intake_name'];
            }
        ];

        $preRegisteredCount = [
            'label' => 'Pre-registered',
            'value' => function ($model) use ($preRegisteredcount) {
                foreach ($preRegisteredcount as $count) {

                    if ($model['intake_name'] === $count['intake_name']) {
                        return $count['student_count'];
                    }
                }
                return 0;
            }
        ];

        $RegisteredCount = [
            'label' => 'Registered',
            'value' => function ($model) use ($registeredCount) {
                foreach ($registeredCount as $count) {

                    if ($model['intake_name'] === $count['intake_name']) {
                        return $count['student_count'];
                    }
                }
                return 0;
            }
        ];


        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],

            $progCol,
            $preRegisteredCount,
            $RegisteredCount,

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
                        'onclick' => 'location.href="' . Yii::$app->urlManager->createUrl(['student-records/admission-report/prog-report', 'intake_name' => $model['intake_name']]) . '";',
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
                    'heading' => '<h5 class="text-center">Intake Admission Report</h5>'
                ],
                'persistResize' => false,
                'itemLabelSingle' => 'intake',
                'itemLabelPlural' => 'intakes',
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