<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\modules\studentRegistration\models\StudentProgCurriculum $model
 * @var app\modules\studentRegistration\models\search\RegisteredStudentsSearch $regStudentsSearchModel
 * @var yii\data\ActiveDataProvider $regStudentsDataProvider
 */

use app\modules\studentRegistration\helpers\GridExport;
use app\modules\studentRegistration\helpers\SmisHelper;
use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
echo \app\helpers\SmisHelper::createBreadcrumb([
    'Student registration' => Url::to(['/student-registration']),
    'Registered students' => null
]);
?>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <?php
                $admRefCol = [
                    'attribute' => 'adm_refno',
                    'label' => 'Admission ref',
                ];
                $regNumCol = [
                    'attribute' => 'registration_number',
                    'label' => 'Registration number',
                ];
                $surnameCol = [
                    'attribute' => 'student.surname',
                    'label' => 'Surname',
                ];
                $otherNamesCol = [
                    'attribute' => 'student.other_names',
                    'label' => 'Other names',
                ];
                $progCodeCol = [
                    'attribute' => 'progCurriculum.programme.prog_code',
                    'label' => 'Prog code',
                ];
                $progNameCol = [
                    'attribute' => 'progCurriculum.programme.prog_full_name',
                    'label' => 'Prog name',
                ];
                $regDateCol = [
                    'attribute' => 'student.registration_date',
                    'label' => 'Registration date',
                    'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'presetDropdown' => true,
                        'convertFormat' => true,
                        'includeMonthsFilter' => true,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'locale' => ['format' => 'Y-m-d'],
                            'separator' => ' to '
                        ],
                        'options' => [
                            'id' => 'registered-students-grid-reg-date'
                        ]
                    ],
                    'value' => function ($model) {
                        return SmisHelper::formatDate($model['student']['registration_date'], 'd-m-Y');
                    },
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $admRefCol,
                    $regNumCol,
                    $surnameCol,
                    $otherNamesCol,
                    $progCodeCol,
                    $progNameCol,
                    $regDateCol
                ];

                try {
                    $title = 'Registered students';
                    $fileName = 'registered_students';

                    echo GridView::widget([
                        'id' => 'registered-students-grid',
                        'dataProvider' => $regStudentsDataProvider,
                        'filterModel' => $regStudentsSearchModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => [
                            '{export}',
                            '{toggleData}',
                        ],
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export students'
                        ],
                        'exportConfig' => [
                            GridViewInterface::EXCEL => GridExport::exportExcel([
                                'filename' => $fileName,
                                'worksheet' => $fileName
                            ]),
                            GridViewInterface::PDF => GridExport::exportPdf([
                                'filename' => $fileName,
                                'title' => $title,
                                'subject' => 'registered students',
                                'keywords' => 'registered students',
                                'contentBefore' => '',
                                'contentAfter' => '',
                                'centerContent' => $title,
                            ]),
                        ],
                        'panel' => [
                            'heading' => 'Registered students',
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'student',
                        'itemLabelPlural' => 'students',
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