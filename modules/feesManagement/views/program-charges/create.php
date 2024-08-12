<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/9/2024
 * @time: 8:10 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var yii\data\ActiveDataProvider $chargesProvider
 * @var app\modules\feesManagement\models\search\ItemChargesSearch $filterModel
 * @var array $frequencies
 * @var array $academicYears
 * @var array $programs
 * @var array $semesters
 * @var array $studyLevels
 * @var string $progCurrId
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $title;
?>

    <!-- Content Header (Page header) -->
<?php
$breadcrumbUrls = [
    'Fees management' => Url::to(['/fees-management']),
    'Programs' => Url::to(['/fees-management/program-charges/programs']),
    'Program charges' => Url::to(['/fees-management/program-charges/list-charges', 'progCurrId' => $progCurrId]),
    'Add charge to program' => null
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
                                Add new program charges
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="prog-charges">
                                <div class="loader"></div>
                                <div class="error-display alert text-center" role="alert"></div>
                            </div>
                            <ul>
                                <li>For programs with <span class="text-info"> NON-INTEGRATED</span> billing type,
                                    <span class="text-info"> IGNORE </span>
                                    <span class="text-info"> LEVEL OF STUDY and SEMESTER</span></li>

                                <li>For programs with <span class="text-danger">REGULAR/INTEGRATED</span> billing type,
                                    you
                                    <span class="text-danger"> MUST</span> provide
                                    <span class="text-danger"> LEVEL OF STUDY and SEMESTER</span></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form id="prog-charges-form">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="year" class="required-control-label">Academic year</label>
                                            <select class="custom-select form-control" id="year" name="year">
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
                                            <label for="program" class="required-control-label">Program</label>
                                            <select class="custom-select form-control" id="program" name="program">
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
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="level">Year of study</label>
                                            <select class="custom-select form-control" id="level" name="level">
                                                <option value="">-- select --</option>
                                                <?php foreach ($studyLevels as $studyLevel): ?>
                                                    <option value="<?= $studyLevel['programme_level_id'] ?>">
                                                        <?= $studyLevel['programme_level_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="semester-id">Semester</label>
                                            <select class="custom-select form-control" id="semester" name="semester">
                                                <option value="">-- select --</option>
                                                <?php foreach ($semesters as $semester): ?>
                                                    <option value="<?= $semester['semester_code'] ?>">
                                                        <?= $semester['semster_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-10 offset-1">
                    <?php
                    $chargeDescCol = [
                        'attribute' => 'fee_description',
                        'label' => 'DESCRIPTION',
                        'vAlign' => 'middle'
                    ];
                    $amountCol = [
                        'label' => 'AMOUNT',
                        'vAlign' => 'middle',
                        'width' => '14%',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $name = 'charge-' . $model['fee_code'] . '-amount';
                            return '<input type="number" id="' . $name . '" class="amount form-control" name="' . $name . '"
                                min="0" oninput="validity.valid||(value=\'\');"/>';
                        }
                    ];
                    $frequencyCol = [
                        'label' => 'FREQUENCY',
                        'vAlign' => 'middle',
                        'width' => '14%',
                        'format' => 'raw',
                        'value' => function () use ($frequencies) {
                            $options = '<option value="">-- select --</option>';
                            if (!empty($frequencies)) {
                                foreach ($frequencies as $frequency) {
                                    $options .= '<option value="' . $frequency['billing_frequency_id'] . '">' . $frequency['name'] . '</option>';
                                }
                            }
                            return '<select class="freq form-control">' . $options . '</select>';
                        }
                    ];
                    $startDateCol = [
                        'label' => 'START DATE',
                        'vAlign' => 'middle',
                        'width' => '14%',
                        'format' => 'raw',
                        'value' => function () {
                            return '<input type="date" class="start-date form-control"/>';
                        }
                    ];
                    $endDateCol = [
                        'label' => 'END DATE',
                        'vAlign' => 'middle',
                        'width' => '14%',
                        'format' => 'raw',
                        'value' => function () {
                            return '<input type="date" class="end-date form-control"/>';
                        }
                    ];

                    $gridColumns = [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $widget) {
                                return [
                                    'value' => $model['fee_code']
                                ];
                            }
                        ],
                        $chargeDescCol,
                        $amountCol,
                        $frequencyCol,
                        $startDateCol,
                        $endDateCol
                    ];

                    $toolbar = [
                        [
                            'content' =>
                                Html::button('<i class="fas fa-plus"></i> Add to program', [
                                    'title' => 'Add charge to program',
                                    'id' => 'add-btn',
                                    'class' => 'btn btn-success btn-spacer btn-sm',
                                ]),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                    ];
                    echo GridView::widget([
                        'id' => 'fee-items-grid',
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
                            'heading' => ''
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
$storeProgramChargesUrl = Url::to(['/fees-management/program-charges/store']);
$getProgBillingFreqUrl = Url::to(['/fees-management/program-charges/billing-type']);
$chargesJs = <<< JS
const storeProgramChargesUrl = '$storeProgramChargesUrl';
const getProgBillingFreqUrl = '$getProgBillingFreqUrl';

const chargesLoader = $('.prog-charges > .loader');
chargesLoader.html(loader);
chargesLoader.hide();
const chargesErrorDisplay =  $('.prog-charges > .error-display');
chargesErrorDisplay.hide();

const progChargesForm = $('#prog-charges-form');
progChargesForm.validate({
    rules: {
        'year': {
            required: true
        },
        'program': {
            required: true
        }
    }
});

$('#fee-items-grid-pjax').on('click', '#add-btn', function (e){
    e.preventDefault();
    if(getSelectedIds('#fee-items-grid').length === 0){
        alert('You must select a charge item to add to this program');
    }else{
        let progCharges = [];
        let fieldsMissing = false;
        let endDateIsBehind = false;
        
        $('table > tbody').find('tr.table-danger').each(function (e){
            let amountElm = $(this).find('.amount');
            let amountName = amountElm.attr('name');
            let feeCode = amountName.split('-')[1];
            
            let freqElm = $(this).find('.freq');
            let startDateElm = $(this).find('.start-date');
            let endDateElm = $(this).find('.end-date');
            
            if(amountElm.val() !== '' && freqElm.val() !== '' && startDateElm.val() !== '' && endDateElm.val() !== '') {
                if(new Date(endDateElm.val()) < new Date(startDateElm.val())) {
                    endDateIsBehind = true;
                }else{
                    let progCharge = {  
                        'feeCode' : feeCode,
                        'amount' : amountElm.val(),
                        'freq' : freqElm.val(),
                        'startDate' : startDateElm.val(),
                        'endDate' : endDateElm.val()
                    };
                    progCharges.push(progCharge);
                }
            }else{
                fieldsMissing = true;
            }
        });
        
        if(fieldsMissing){
           alert('For each charge you must provide the following: AMOUNT, FREQUENCY, START and END DATES') 
        }else if(endDateIsBehind){
            alert('For each charge the START DATE must be behind the END DATE')        
        }else if(progChargesForm.valid()){
            let progCurrId = $('#program').val();
            let level = $('#level').val();
            let semester = $('#semester').val();
            
            /**
            * Two things are happening here after we validate the form:
            * 1) We check for the billing type of the selected program
            * 2) We submit the program charges payload
            * First, we send a xhr request to get the billing type of the program
            * and wait for the promise to resolve before attempting step two. Step 2 needs to know the billing type from the request.
            * Second, If the type is Regular/Integrated, ie program is billed per semester, we require that level of study and 
            * semester values be provided in the payload. Else we ignore these fields.
            */
            chargesErrorDisplay.hide();
            chargesLoader.show();
            axios.get(getProgBillingFreqUrl, {
                params: {
                    progCurrId: progCurrId
                }
            })
            .then(response => {
                if(response.data.success){
                    chargesLoader.hide();
                    if(response.data.billingType === 'Regular/Integrated' && (level === '' || semester === '')) {
                        chargesErrorDisplay.html('Billing type of this program is Regular/Integrated. You must provide LEVEL OF STUDY and SEMESTER.'); 
                        chargesErrorDisplay.show();       
                    } else {
                        if(confirm('Add selected charges to program?')){
                            chargesErrorDisplay.hide();
                            chargesLoader.show();
                            $.ajax({
                                url: storeProgramChargesUrl,
                                type: 'POST',
                                data: {
                                    'progCharges': progCharges,
                                    'progCurrId' : progCurrId,
                                    'acadSessionId': $('#year').val(),
                                    'levelOfStudy' : level,
                                    'semester' : semester
                                }
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
                        }
                    }
                }else{
                    chargesLoader.hide();
                    chargesErrorDisplay.html('Fetching program billing type: ' + response.data.message) 
                    chargesErrorDisplay.show();
                }
            })
            .catch(error => {
                console.error(error.message);
                chargesLoader.hide();
                chargesErrorDisplay.html('Fetching program billing type: ' + error.message) 
                chargesErrorDisplay.show();
            });
        }else{ // Validation errors
            chargesLoader.hide();
            chargesErrorDisplay.html('There were errors below, correct them and try submitting again.');   
            chargesErrorDisplay.show();
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

