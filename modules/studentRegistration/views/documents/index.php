<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\modules\studentRegistration\models\AdmittedStudent $model
 * @var app\modules\studentRegistration\models\search\DocumentsSearch $docsSearchModel
 * @var yii\data\ActiveDataProvider $docsDataProvider
 * @var array $intakesList
 * @var array $intakeSourcesList
 * @var array $programmesList
 * @var array $categoriesList
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<?php
echo SmisHelper::createBreadcrumb([
    'Student registration' => Url::to(['/student-registration']),
    'Students to verify' => null
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
                        'vAlign' => 'middle',
                    ];
                    $surnameCol = [
                        'attribute' => 'surname',
                        'label' => 'Surname',
                        'vAlign' => 'middle'
                    ];
                    $otherNamesCol = [
                        'attribute' => 'other_names',
                        'label' => 'Other names',
                        'vAlign' => 'middle'
                    ];
                    $primaryEmailCol = [
                        'attribute' => 'primary_email',
                        'label' => 'Primary email',
                        'vAlign' => 'middle'
                    ];
                    $primaryPhoneCol = [
                        'attribute' => 'primary_phone_no',
                        'label' => 'Primary phone',
                        'vAlign' => 'middle'
                    ];
                    $applicationRefCol = [
                        'attribute' => 'application_refno',
                        'label' => 'Application ref',
                        'vAlign' => 'middle',
                        'value' => function($model){
                            if(empty($model['application_refno'])){
                                return '';
                            }
                            return $model['application_refno'];
                        }
                    ];
                    $intakeNameCol = [
                        'attribute' => 'intake.intake_code',
                        'label' => 'Intake',
                        'filterType' => GridViewInterface::FILTER_SELECT2,
                        'filter' => $intakesList,
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'filterWidgetOptions' => [
                            'options'=>[
                                'id' => 'documents-grid-intakes',
                                'placeholder' => '--- all ---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'autoclose' => true
                            ]
                        ],
                        'value' => function($model){
                            return $model['intake']['intake_name'];
                        }
                    ];
                    $intakeSourceCol = [
                        'attribute' => 'intakeSource.source_id',
                        'label' => 'Source',
                        'filterType' => GridViewInterface::FILTER_SELECT2,
                        'filter' => $intakeSourcesList,
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'filterWidgetOptions' => [
                            'options'=>[
                                'id' => 'documents-grid-sources',
                                'placeholder' => '--- all ---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'autoclose' => true
                            ]
                        ],
                        'value' => function($model){
                            return $model['intakeSource']['source'];
                        }
                    ];
                    $categoryNameCol = [
                        'attribute' => 'category.std_category_id',
                        'label' => 'Category',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => $categoriesList,
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'filterWidgetOptions' => [
                            'options'=>[
                                'id' => 'documents-grid-categories',
                                'placeholder' => '--- all ---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'autoclose' => true
                            ]
                        ],
                        'value' => function($model){
                            return $model['category']['std_category_name'];
                        }
                    ];
                    $programmeNameCol = [
                        'attribute' => 'programme.prog_code',
                        'label' => 'Programme',
                        'filterType' => GridViewInterface::FILTER_SELECT2,
                        'filter' => $programmesList,
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'width' => '15%',
                        'filterWidgetOptions' => [
                            'options'=>[
                                'id' => 'documents-grid-programmes',
                                'placeholder' => '--- all ---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'autoclose' => true
                            ]
                        ],
                        'value' => function($model){
                            return $model['programme']['prog_code'] . ' - ' .
                                $model['programme']['prog_short_name'];
                        }
                    ];

                    $actionsCol = [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{verifyDocuments}',
                        'contentOptions' => [
                            'style'=>'white-space:nowrap;',
                            'class'=>'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'verifyDocuments' => function ($url, $model){
                                return Html::a('verify documents',
                                    Url::to([
                                        '/student-registration/documents/verify',
                                        'admRefNo' => $model['adm_refno'],
                                    ]),
                                    [
                                        'title' => 'Verify documents',
                                        'class' => 'btn-link',
//                                        'target' => '_blank',
//                                        'data-pjax' => '0'
                                    ]
                                );
                            }
                        ],
                        'hAlign' => 'center',
                    ];

                    $gridColumns = [
                        ['class' => 'kartik\grid\SerialColumn'],
                        $admRefCol,
                        $surnameCol,
                        $otherNamesCol,
//                        $primaryEmailCol,
//                        $applicationRefCol,
//                        $programmeNameCol,
//                        $intakeNameCol,
//                        $intakeSourceCol,
//                        $categoryNameCol,
                        $actionsCol
                    ];

                    try{
                        echo GridView::widget([
                            'id' => 'documents-grid',
                            'dataProvider' => $docsDataProvider,
                            'filterModel' => $docsSearchModel,
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
                            'panel' => [
                                'heading' => 'Students with documents awaiting verification',
                            ],
                            'persistResize' => false,
                            'toggleDataOptions' => ['minCount' => 50],
                            'itemLabelSingle' => 'student',
                            'itemLabelPlural' => 'students',
                        ]);
                    }catch (Throwable $ex) {
                        $message = $ex->getMessage();
                        if(YII_ENV_DEV) {
                            $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                        }
                        throw new ServerErrorHttpException($message, 500);
                    }
                ?>
            </div>
        </div>
    </div>
</section>

