<?php
/*
 * @Author: Jeff Rureri Wahome 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-01-29 13:56:43 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-01-30 10:32:02
 * @Description: file:///home/user/Documents/smis/modules/studentRecords/views/admission-report/prog-report.php
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
    'Admission Intake Report' => Url::to(['admission-report/']),
    $title => null
]);
?>

<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-body text-center">
                <h5>INTAKE: <?php echo $intake ?> </h5>
            </div>
        </div>

        <?php
        $progCol = [
            'label' => 'Programme',
            'value' => function ($model) {
                return $model['prog_full_name'];
            }
        ];

        $preRegisteredCount = [
            'label' => 'Pre-registered',
            'value' => function ($model) use ($preRegisteredcount) {
                foreach ($preRegisteredcount as $count) {

                    if ($model['prog_full_name'] === $count['prog_full_name']) {
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

                    if ($model['prog_full_name'] === $count['prog_full_name']) {
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
                    'heading' => '<h5 class="text-center">Programme Admission Report</h5>'
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