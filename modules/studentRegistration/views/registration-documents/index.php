<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 8/29/2023
 * @time: 11:37 AM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\modules\studentRegistration\models\RequiredDocument $model
 * @var app\modules\studentRegistration\models\search\RegistrationDocumentsSearch $docsSearchModel
 * @var yii\data\ActiveDataProvider $docsProvider
 * @var string[] $categoriesList
 * @var string[] $requiredStatusList
 */

use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

    <!-- Content Header (Page header) -->
<?php
echo \app\helpers\SmisHelper::createBreadcrumb([
    'Student registration' => Url::to(['/student-registration']),
    'Registration documents setup' => null
]);
?>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 offset-1">
                <?php
                $studentCategoryCol = [
                    'attribute' => 'studentCategory',
                    'label' => 'CATEGORY',
                    'vAlign' => 'middle',
                    'group' => true,
                    'filterType' => GridViewInterface::FILTER_SELECT2,
                    'filter' => $categoriesList,
                    'format' => 'raw',
                    'width' => '20%',
                    'filterWidgetOptions' => [
                        'options'=>[
                            'id' => 'reg-docs-categories',
                            'placeholder' => '--- all ---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'autoclose' => true
                        ]
                    ],
                    'value' => function($model){
                        return $model['std_category_name'];
                    }
                ];
                $docNameCol = [
                    'attribute' => 'docName',
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['document_name'];
                    }
                ];
                $docDescCol = [
                    'attribute' => 'docDesc',
                    'label' => 'DESCRIPTION',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['document_desc'];
                    }
                ];
                $requiredCol = [
                    'attribute' => 'docRequired',
                    'label' => 'REQUIRED',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        if($model['required']){
                            return 'Required';
                        }
                        return 'Not Required';
                    },
                    'filterType' => GridViewInterface::FILTER_SELECT2,
                    'filter' => $requiredStatusList,
                    'format' => 'raw',
                    'width' => '10%',
                    'filterWidgetOptions' => [
                        'options'=>[
                            'id' => 'reg-docs-required',
                            'placeholder' => '--- all ---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'autoclose' => true
                        ]
                    ],
                ];
                $actionsCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{editDocuments}',
                    'contentOptions' => [
                        'style'=>'white-space:nowrap;',
                        'class'=>'kartik-sheet-style kv-align-middle'
                    ],
                    'buttons' => [
                        'editDocuments' => function ($url, $model){
                            return Html::a('<i class="fa fa-edit" aria-hidden="true">&nbsp;</i>edit',
                                Url::to('javascript:void(0)'),
                                [
                                    'title' => 'Edit document',
                                    'class' => 'btn-link edit-document',
                                    'documentId' => $model['document_id']
                                ]
                            );
                        }
                    ],
                    'hAlign' => 'center',
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $studentCategoryCol,
                    $docNameCol,
                    $docDescCol,
                    $requiredCol,
                    $actionsCol
                ];

                $toolbar = [
                    [
                        'content' =>
                            Html::button('<i class="fas fa-plus""></i> New document', [
                                'title' => 'New document',
                                'id' => 'btn-new-doc',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]) . '&nbsp' .
                            Html::a('<i class="fa fa-angle-double-right"></i> Add docs to categories',
                                Url::to([
                                    '/student-registration/registration-documents/associate-docs-with-categories',
                                    'scope' => 'add-docs'
                                ]),
                                [
                                    'title' => 'Add documents to student categories',
                                    'class' => 'btn btn-success btn-spacer'
                                ]
                            ). '&nbsp' .
                            Html::a('<i class="fas fa-x""></i> Remove docs from categories',
                                Url::to([
                                    '/student-registration/registration-documents/associate-docs-with-categories',
                                    'scope' => 'remove-docs'
                                ]),
                                [
                                    'title' => 'Remove documents from student categories',
                                    'class' => 'btn btn-danger btn-spacer'
                                ]
                            ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{export}',
                    '{toggleData}',
                ];

                try{
                    echo GridView::widget([
                        'id' => 'documents-grid',
                        'dataProvider' => $docsProvider,
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
                        'toolbar' => $toolbar,
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export documents'
                        ],
                        'panel' => [
                            'heading' => 'Registration documents',
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'document',
                        'itemLabelPlural' => 'documents',
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

<?php
echo $this->render('create');
echo $this->render('edit');





