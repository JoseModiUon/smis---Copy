<?php

use app\models\OrgAcademicSession;
use app\models\ReceiptSponsorFund;
use app\models\SmStudentSponsor;
use app\models\SponsorBeneficiary;
use app\modules\feesManagement\models\search\SponsorBeneficiarySearch;
use kartik\dialog\Dialog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ReceiptSponsorFundSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$params = Yii::$app->request->getQueryParams();
$sponsor_name = SmStudentSponsor::findOne($params['sponsor_id'])->sponsor_name;
$this->title = 'Receipt Sponsor Funds';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsor', 'url' => ['/fees-management/sm-student-sponsor', 'sponsor_id' => $params['sponsor_id']]];
$this->params['breadcrumbs'][] = $this->title;

// Get the sponsor_id from the request
$sponsorId = Yii::$app->request->get('sponsor_id');
// Get the Sponsor name based on the sponsor_id
$sponsorName = $sponsorId ? SmStudentSponsor::findOne($sponsorId)->sponsor_name : '';

echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);

?>
<div class="receipt-sponsor-fund-index">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
                <div>Sponsor: <?= $sponsor_name ?></div>


                <!-- The Modal -->
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">File upload form</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Form -->
                            <?= $this->render('_search_new', ['model' => new SponsorBeneficiarySearch()]) ?>

                        </div>
                    </div>
                </div>
            </h3>


            <div class="d-flex justify-content-end mb-3" style="margin-top: 50px;">
                <div style="margin-right: 20px;">
                    <?= Html::a(
                        '<i class="bi bi-plus-lg"></i> Create Receipt',
                        ['create'],
                        ['class' => 'btn btn-lg btn-primary']
                    )
                    ?>
                </div>
                <?= Html::button(
                    '<i class="bi bi-filetype-xls"></i> Dowload Excel',
                    [
                        'class' => 'btn btn-success btn-lg',
                        'onclick' => 'js:excel()'

                    ]
                )
                ?>
            </div>


            <div>
                <span class="fw-bold fs-6">
                    <?= $sponsorName ?>
                </span>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Source Reference',
                        'attribute' => 'source_reference',
                        // 'value' => function ($model) {
                        //     return SmStudentSponsor::findOne($model->sponsor_id)->sponsor_name;
                        // }
                    ],

                    [
                        'label' => 'Credited Amount',
                        'attribute' => 'amount',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDecimal($model->amount, 2);
                        },
                    ],
                    [
                        'label' => 'Balance',
                        'attribute' => 'balance',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDecimal($model->findBalance(), 2);
                        }
                    ],
                    [
                        'label' => 'Status',
                        'attribute' => 'trans_type',
                    ],
                    'receipt_date',
                    //'pv_no',
                    'cheque_no',
                    [
                        'label' => 'Academic Session',
                        'attribute' => 'academic_session',
                        'value' => function ($model) {
                            return OrgAcademicSession::findOne($model->academic_session)->acad_session_name;
                        }
                    ],

                    'user_id',
                    //'post_date',
                    [
                        'label' => 'No. of Beneficiaries',
                        'attribute' => 'no_of_beneficiaries',
                    ],
                    [
                        'label' => 'Allocated Beneficiaries',
                        'attribute' => 'allocated_beneficiaries',
                        'value' => function ($model) {
                            return $model->getTotalBeneficiaries();
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{view}{post}{excel}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'excel' => function ($url, $model, $key) {
                                $receipt_id = $model->receipt_sponsor_fund_id;
                                return  Html::a(
                                    ' upload ',
                                    [
                                        '#',
                                    ],
                                    [
                                        'class' => ' btn-link bi bi-file-earmark-arrow-up text-default px-2',
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#myModal",
                                        'onclick' => "js:setReceiptId($receipt_id)"

                                    ]
                                );
                            },
                            'post' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Transfer for Posting ',
                                    [
                                        '/fees-management/receipt-sponsor-fund/list-beneficiary',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                        'source_reference' => $model->source_reference,

                                    ],
                                    ['class' => ' bi btn-link bi bi-file-post text-default px-2']
                                );
                            },

                            'view' => function ($url, $model, $key) {
                                return Html::a(
                                    'View',
                                    [
                                        '/fees-management/receipt-sponsor-fund/list-beneficiary-view',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                        'source_reference' => $model->source_reference,
                                    ],
                                    [
                                        'class' => 'btn-link bi bi-eye text-default px-2',
                                    ]
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>

<?php
$urlSaveMarks = Url::to(['/courseRegistration/reports/mark-grade']);
$urlGetExcelFile = Url::to(['/fees-management/receipt-sponsor-fund/get-excel-file']);
$urlParseExcel = Url::to(['/fees-management/receipt-sponsor-fund/upload-excel-file']);
$urlGetGrade = Url::to(['/courseRegistration/reports/find-grade']);
$allData = json_encode($dataProvider->query->all());
$allDataEncoded = base64_encode($allData);


$marksJs = <<< JS
let urlSaveMarks = '$urlSaveMarks';
let urlGetGrade = '$urlGetGrade';
let urlParseExcel = '$urlParseExcel';
let urlGetExcelFile = '$urlGetExcelFile'
let allDataEncoded = '$allDataEncoded'
let sponsorName = '$sponsor_name'

const data =$allData
const csrfToken = $('meta[name="csrf-token"]').attr("content");



function getBase64(file) {
    var fileReader = new FileReader();
    if (file) {
        fileReader.readAsDataURL(file);
    }
    return new Promise((resolve, reject) => {
      fileReader.onload = function(event) {
        resolve(event.target.result);
      };
    })
}



const excel = () => {
    getFile()
}

const getFile = () => {
    $.ajax({
        url: urlGetExcelFile,
        type: 'POST',
        data: {data:data},
        dataType: 'json',  
        success: function(resp) {
            console.log(resp)
            let url = URL.createObjectURL(dataURItoBlob(resp.data));
            let link = document.createElement('a');
            link.href = url;
            link.download = sponsorName +'.xlsx';
            link.click();

        },
        error: function(err) {
            // $('#app-is-loading-modal').modal('hide');
            krajeeDialog.alert('could not get file.');
        }
    })
}

function dataURItoBlob(dataURI) {
    const byteString = window.atob(dataURI);
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const int8Array = new Uint8Array(arrayBuffer);
    for (let i = 0; i < byteString.length; i++) {
        int8Array[i] = byteString.charCodeAt(i);
    }
    const blob = new Blob([int8Array], { type: 'application/vnd.ms-excel'});
    return blob;
}



function ajaxData(data) {
    krajeeDialog.confirm('Are you sure you want to submit these marks?', function (result) {
        if (result) {       
            $.ajax({
                url: urlSaveMarks,
                type: 'POST',
                data: data,
                dataType: 'json',  
                success: function(data) {
                    // $('#app-is-loading-modal').modal('hide');
                    krajeeDialog.alert(data.message);
                    // window.location.reload()
                },
                error: function(err) {
                    // $('#app-is-loading-modal').modal('hide');
                    krajeeDialog.alert('Updating marks failed.');
                }
            })
        }
    }); 
}

function ajaxDataGrade(data) {
    $.ajax({
        url: urlGetGrade,
        type: 'POST',
        data: data,
        dataType: 'json',  
        success: function(resp) {
            const obj = {id:data.grade_id, grade: resp.grade}
            setGradeMark(obj)
        },
        error: function(err) {
            // $('#app-is-loading-modal').modal('hide');
            krajeeDialog.alert('Updating marks failed.');
        }
    })
}




function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

const findExamMark = (id)  => {
    const exam = document.getElementById(id)
    if(exam.value) {
        return exam.value
    }
    return 0
}

const setFinalMark = (id, final_mark) => {
    const ele = document.getElementById(id)
    ele.value = final_mark
    if(final_mark > 100) {
        ele.classList.add('is-invalid')
        document.getElementById('save-marks-btn').disabled = true
    } else {
        if (ele.classList.contains('is-invalid')) {
            document.getElementById('save-marks-btn').disabled = false
            ele.classList.remove("is-invalid");
        }
    }
}

const setReceiptId = (receiptId) => {
    const hiddenInput = document.getElementById('receipt_sponsor_fund_id_js')
    hiddenInput.value = receiptId
    console.log(hiddenInput)
}

JS;
$this->registerJs($marksJs, yii\web\View::POS_END);
