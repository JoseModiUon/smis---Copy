<?php


use Yii;
use yii\helpers\Url;


use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\feesManagement\models\BankAccounts;
use app\modules\feesManagement\models\PaymentModes;
use app\modules\feesManagement\models\PaymentTypes;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */

$get = Yii::$app->request->get();

$receipt_id = '';
if (array_key_exists('SponsorBeneficiarySearch', $get)) {
    $receipt_id = $get['SponsorBeneficiarySearch']['receipt_sponsor_fund_id'];
} else {
    $receipt_id = $get['receipt_sponsor_fund_id'] ?? '';
}
?>



<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([

        'options' => ['enctype' => 'multipart/form-data'],
        'id' => 'uploadFormExcel',
    ]); ?>

    <div class="modal-body container px-2">

        <div class="row mb-2">
            <div class="col-md-12">
                <?= $form->field($model, 'file')
                    ->label('Select File', ['class' => 'mb-2 fw-bold'])
                    ->fileInput(['name' => 'file-upload', 'id' => 'file-upload', 'class' => 'form-control']) ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">

                <?= $form->field($model, 'receipt_sponsor_fund_id')->hiddenInput([
                    'id' => 'receipt_sponsor_fund_id_js'
                ])->label(false) ?>
            </div>
        </div>

        <!-- <div class="row mb-2">
            <div class="col-md-4">
                <?php
                /*
                $sp = BankAccounts::find()->select(['brank_account_id', 'concat(bank_account, \' \', account_details) as desc'])->asArray()->all();
                $data = ArrayHelper::map($sp, 'brank_account_id', 'desc');
                echo $form
                    ->field($model, 'bank_account')
                    ->label('Bank Account', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => [
                            'placeholder' => 'Select Bank Account ...',
                            'onchange' => 'js:findBranch(this)'

                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);*/
                ?>
            </div>
            <div class="col-md-4">
                <?php

                $sp = PaymentModes::find()->select(['payment_mode_id', 'description'])->asArray()->all();
                $data = ArrayHelper::map($sp, 'payment_mode_id', 'description');
                array_unshift($data, 'ALL MODES');

                echo $form
                    ->field($model, 'payment_mode')
                    ->label('Payment Mode', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Payment Mode ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
            </div>
            <div class="col-md-4">
                <?php
                $data = [
                    'ALL' => 'ALL'
                ];
                echo $form
                    ->field($model, 'posting')
                    ->label('Posting', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Posting ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
            </div>
        </div> -->

        <!-- 
        <div class="row mb-2">
            <div class="col-md-4">
                <?php
                /*
                echo $form
                    ->field($model, 'branch')
                    ->label('Deposit Branch', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => [],
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Payment Mode ...', 'id' => 'branch-name-element'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    */
                ?>
            </div>
            <div class="col-md-4">
                <?php
                /*
                $sp = PaymentTypes::find()->select(['fee_code', 'fee_description'])->asArray()->all();
                $data = ArrayHelper::map($sp, 'fee_code', 'fee_description');
                echo $form
                    ->field($model, 'payment_type')
                    ->label('Payment Type', ['class' => 'mb-2 fw-bold'])
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Payment Type ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    */
                ?>
            </div>
        </div> -->


        <?php  ?>
        <div class="row mb-2">
            <div class="d-flex justify-content-between modal-footer">

                <input type='submit' class='btn btn-info' value='Upload' id='btn_upload' accept=".xls,.xlsx">

                <button type="button" id="uploadFormExcelClsBtn" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$findBranch = Url::to(['/fees-management/receipt-sponsor-fund/find-branch']);
$urlPostBeneficiary = Url::to(['/fees-management/receipt-sponsor-fund/post-beneficiary']);







$findBranchJs = <<< JS
const findBranchUrl = '$findBranch'
let urlPostBeneficiary = '$urlPostBeneficiary';

let file = {}

document.getElementById('btn_upload').addEventListener('click', (e) => uploadForm(e))

document.getElementById('file-upload').addEventListener('change', (f) => {
    file = f.target.files[0]; // FileList object
})

const setBranchName = (branch) => {
    const select = document.getElementById('branch-name-element')
    select.disabled = false
    var option = document.createElement("option");
    option.value = branch.branch_code;
    option.innerHTML = branch.branch_name;
    select.appendChild(option)
    select.value =  branch.branch_code
}

const formData = (data)  => {

    const arr = []
    const formData = new FormData();

    for (const key in data) {
        const item = data[key]
        if(typeof item === 'object') {
            formData.append(key,JSON.stringify(item));
        } else {
            formData.append(key,item);
        }
    }

    formData.append("_csrf", csrfToken)

    return formData
}

const redirect = () => {
    const receiptId = document.getElementById('receipt_sponsor_fund_id_js').value
    window.location = '/fees-management/receipt-sponsor-fund/list-beneficiary-view?receipt_sponsor_fund_id=' + receiptId;
}

const postBeneficiary = async (data) => {
    let dataJson = {}
    krajeeDialog.confirm('Are you sure you want to submit these beneficiaries?',  async (result) => {
        if (result) {
            const options = { method: 'post', body: formData(data) }
            const resp = await fetch(urlPostBeneficiary,options)
            dataJson =  await resp.json()
            document.getElementById('uploadFormExcelClsBtn').click()
            // krajeeDialog.alert(dataJson.msg);
            redirect()
        }
    });

    return dataJson
}

const parseExcel = async (formData) => {

    try {
        const resp = await fetch(urlParseExcel, {method: "POST", body: formData})
        const parseData = await resp.json()
        redirect()

        // const dataJson = await postBeneficiary(parseData.data)
    } catch (error) {
        krajeeDialog.alert(error);
    }
};

const uploadForm = (e) => {
    e.preventDefault()
    const form = document.getElementById('uploadFormExcel')
    const formData = new FormData(form);
    formData.append("file", file);
    parseExcel(formData)
}



const findBranch = async (e) => {
    const bank_account_id = e.value
    const response = await fetch(findBranchUrl, 
        {method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({data:{
            bank_account_id:bank_account_id,
        }}),
    })
    const data = await response.json();
    setBranchName(data)
}

JS;
$this->registerJs($findBranchJs, yii\web\View::POS_END);
