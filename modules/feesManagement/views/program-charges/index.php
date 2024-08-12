<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/8/2024
 * @time: 11:03 AM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var yii\data\ActiveDataProvider $chargesProvider
 * @var app\modules\feesManagement\models\search\ProgCurrChargesSearch $filterModel
 * @var string $panelHeader
 * @var bool $regularIntegrated
 * @var array $academicYears
 * @var array $programs
 * @var string $progCurrId
 */

use app\helpers\SmisHelper;
use app\modules\feesManagement\models\BillingFrequency;
use kartik\grid\GridView;
use kartik\grid\GridViewInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $title;
?>

    <!-- Content Header (Page header) -->
<?php
$breadcrumbUrls = [
    'Fees management' => Url::to(['/fees-management']),
    'Programs' => Url::to(['/fees-management/program-charges/programs']),
    'Program charges' => null
];
echo SmisHelper::createBreadcrumb($breadcrumbUrls);
?>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Copy program charges
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="prog-charges">
                                <div class="loader"></div>
                                <div class="error-display alert text-center" role="alert"></div>
                            </div>
                            <ul>
                                <li>Charges will be copied from the current program</li>
                                <li>The Source and destination programs <span class="text-danger"> MUST </span> be of
                                    the same billing type
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form id="prog-charges-form">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="from-year" class="required-control-label">From academic
                                                year</label>
                                            <select class="custom-select form-control" id="from-year" name="from-year">
                                                <option value="">-- select --</option>
                                                <?php foreach ($academicYears as $academicYear): ?>
                                                    <option value="<?= $academicYear['acad_session_id'] ?>">
                                                        <?= $academicYear['acad_session_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="to-year" class="required-control-label">To academic year</label>
                                            <select class="custom-select form-control" id="to-year" name="to-year">
                                                <option value="">-- select --</option>
                                                <?php foreach ($academicYears as $academicYear): ?>
                                                    <option value="<?= $academicYear['acad_session_id'] ?>">
                                                        <?= $academicYear['acad_session_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="to-program" class="required-control-label">To Program</label>
                                            <select class="custom-select form-control" id="to-program"
                                                    name="to-program">
                                                <option value="">-- select --</option>
                                                <?php foreach ($programs as $program): ?>
                                                    <option value="<?= $program['prog_curriculum_id'] ?>">
                                                        <?= $program['prog_code'] . ' - ' . $program['prog_short_name'] .
                                                        ' (' . $program['billing_type_desc'] . ')' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div>
                                        <button type="submit" id="copy-charges-btn" class="btn btn-success">Copy
                                            charges
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-10 offset-1">
                    <?php
                    $academicYearCol = [
                        'label' => 'ACADEMIC YEAR',
                        'attribute' => 'acad_session_name',
                        'group' => true,
                        'width' => '8%'
                    ];
                    $levelCol = [
                        'label' => 'STUDY LEVEL',
                        'vAlign' => 'middle',
                        'attribute' => 'level_of_study',
                        'group' => true,
                        'subGroupOf' => 1,
                        'width' => '5%'
                    ];
                    $semesterCol = [
                        'label' => 'SEMESTER',
                        'vAlign' => 'middle',
                        'attribute' => 'semester',
                        'group' => true,
                        'subGroupOf' => 2,
                        'width' => '5%'
                    ];
                    $chargeDescCol = [
                        'label' => 'DESCRIPTION',
                        'vAlign' => 'middle',
                        'attribute' => 'fee_description'
                    ];
                    $startDateCol = [
                        'label' => 'START DATE',
                        'vAlign' => 'middle',
                        'attribute' => 'start_date',
                        'width' => '8%',
                        'value' => function ($model) {
                            if (empty($model['start_date'])) return '--';
                            else return $model['start_date'];
                        }
                    ];
                    $endDateCol = [
                        'label' => 'END DATE',
                        'vAlign' => 'middle',
                        'attribute' => 'end_date',
                        'width' => '8%',
                        'value' => function ($model) {
                            if (empty($model['end_date'])) return '--';
                            else return $model['end_date'];
                        }
                    ];
                    $amountCol = [
                        'label' => 'AMOUNT',
                        'vAlign' => 'middle',
                        'attribute' => 'amount_charged',
                        'width' => '8%',
                        'format' => ['decimal', 2]
                    ];
                    $frequencyCol = [
                        'label' => 'FREQUENCY',
                        'vAlign' => 'middle',
                        'attribute' => 'billing_freq',
                        'width' => '8%',
                        'filterType' => GridViewInterface::FILTER_SELECT2,
                        'filter' => ArrayHelper::map(BillingFrequency::find()->asArray()->all(), 'billing_frequency_id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                            'class' => [
                                'form-control'
                            ]
                        ],
                        'filterInputOptions' => ['placeholder' => ''],
                    ];
                    $publishCol = [
                        'label' => 'PUBLISHED',
                        'vAlign' => 'middle',
                        'attribute' => 'publish',
                        'width' => '5%',
                    ];
                    $actionCol = [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => 'ACTIONS',
                        'template' => '{edit}',
                        'contentOptions' => ['style' => 'white-space:nowrap;', 'class' => 'kartik-sheet-style kv-align-middle'],
                        'buttons' => [
                            'edit' => function ($url, $model) {
                                return Html::a('edit',
                                    Url::to([
                                        '/fees-management/program-charges/edit',
                                        'chargeTypeId' => $model['charge_type_id']
                                    ]),
                                    [
                                        'title' => 'Edit program charges',
                                        'class' => 'btn btn-link',
                                        'target' => '_blank',
                                        'data-pjax' => '0'
                                    ]
                                );
                            }
                        ]
                    ];

                    $gridColumns = [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $widget) {
                                return [
                                    'value' => $model['charge_type_id']
                                ];
                            }
                        ],
                        $academicYearCol,
                    ];

                    if ($regularIntegrated) {
                        array_push($gridColumns,
                            $levelCol,
                            $semesterCol,
                            $chargeDescCol,
                            $startDateCol,
                            $endDateCol,
                            $amountCol,
                            $frequencyCol,
                            $publishCol
                        );
                    } else {
                        array_push($gridColumns,
                            $chargeDescCol,
                            $startDateCol,
                            $endDateCol,
                            $amountCol,
                            $frequencyCol,
                            $publishCol
                        );
                    }
                    $gridColumns[] = $actionCol;

                    $toolbar = [
                        [
                            'content' =>
                                Html::a('<i class="fa-solid fa-plus"></i> Add program charges',
                                    Url::to(['/fees-management/program-charges/create', 'progCurrId' => $progCurrId]), [
                                        'title' => 'Add program charges',
                                        'class' => 'btn btn-success btn-spacer btn-sm',
                                    ]) . '&nbsp' .
                                Html::button('<i class="fas fa-remove"></i> Delete charges', [
                                    'title' => 'Delete charges',
                                    'id' => 'delete-charges-btn',
                                    'class' => 'btn btn-danger btn-spacer btn-sm',
                                ]),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                    ];

                    echo GridView::widget([
                        'id' => 'prog-charges-grid',
                        'dataProvider' => $chargesProvider,
                        'filterModel' => $filterModel,
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
                        'export' => false,
                        'panel' => [
                            'heading' => $panelHeader
                        ],
                        'persistResize' => false,
                        'itemLabelSingle' => 'charge',
                        'itemLabelPlural' => 'charges',
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </section>

<?php
$copyProgramChargesUrl = Url::to(['/fees-management/program-charges/copy']);
$dropChargesUrl = Url::to(['/fees-management/program-charges/drop']);
$chargesJs = <<< JS
const copyProgramChargesUrl = '$copyProgramChargesUrl';
const dropChargessUrl = '$dropChargesUrl';
const progCurrId = '$progCurrId';
const chargesLoader = $('.prog-charges > .loader');
chargesLoader.html(loader);
chargesLoader.hide();
const chargesErrorDisplay =  $('.prog-charges > .error-display');
chargesErrorDisplay.hide();

const progChargesForm = $('#prog-charges-form');
progChargesForm.validate({
    rules: {
        'to-year': {
            required: true
        },
        'from-year': {
            required: true
        },
        'to-program': {
            required: true
        }
    }
});

$('#copy-charges-btn').click(function (e){
    e.preventDefault();
    if(progChargesForm.valid() && confirm('Copy program charges?')){
        chargesErrorDisplay.hide();
        chargesLoader.show();
        $.ajax({
            url: copyProgramChargesUrl,
            type: 'POST',
            data: progChargesForm.serialize() + '&from-program=' + progCurrId
        }).done(function (data){
            chargesLoader.hide();
             if(!data.success){
                chargesErrorDisplay.html(data.message) 
                chargesErrorDisplay.show();
             }
        }).fail(function (data){
             chargesLoader.hide();
             chargesErrorDisplay.html(data.responseText) 
             chargesErrorDisplay.show();
        });
    }else {
        chargesLoader.hide();
        chargesErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        chargesErrorDisplay.show();
    }
});

$('#prog-charges-grid-pjax').on('click', '#delete-charges-btn', function (e){
    e.preventDefault();
    let charges = getSelectedIds('#prog-charges-grid');
    if(charges.length === 0){
        alert('You must select a charge item to add to delete');
    }else{
       if(confirm('Remove the selected charges from this program?')){
            chargesErrorDisplay.hide();
            chargesLoader.show(); 
            $.ajax({
                url: dropChargessUrl,
                type: 'POST',
                data: {
                    'progCurrId' : progCurrId,
                    'charges' : charges
                }
            }).done(function (data){
                chargesLoader.hide();
                if(!data.success){
                    chargesErrorDisplay.html(data.message);
                    chargesErrorDisplay.show();
                }
            }).fail(function (data){
                chargesLoader.hide();
                chargesErrorDisplay.html(data.responseText);
                chargesErrorDisplay.show();
            });
        }
    }
});

// Get selected rows
function getSelectedIds(gridSelector) {
    let keys = $(gridSelector).yiiGridView('getSelectedRows');
    let ids = [];
    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');

        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    return [...new Set(ids)]
}
JS;
$this->registerJs($chargesJs, yii\web\View::POS_READY);