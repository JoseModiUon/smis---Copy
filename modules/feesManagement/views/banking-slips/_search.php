<?php


use Yii;
use yii\helpers\Url;


use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\feesManagement\models\BankAccounts;
use app\modules\feesManagement\models\FssPaymentTypes;
use app\modules\feesManagement\models\PaymentModes;
use app\modules\feesManagement\models\PaymentTypes;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */


?>



<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row mb-2">
        <div class="col-md-12">

            <?= $form->field($model, 'receipt_sponsor_fund_id')->hiddenInput([
                'value' => ''
            ])->label(false) ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <?php
            $account_model =
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
                ]);
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
                'ALL' => 'ALL',
                'POSTED' => 'POSTED',
                'NOT POSTED' => 'NOT POSTED',
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
    </div>


    <div class="row mb-2">
        <div class="col-md-4">
            <?php
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
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $sp = FssPaymentTypes::find()->select(['payment_type_id', 'payment_desc'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'payment_type_id', 'payment_desc');
            echo $form
                ->field($model, 'payment_type_id')
                ->label('Payment Type', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Payment Type ...', 'value' => '5'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo $form
                ->field($model, 'deposit_from_date')
                ->label('Deposit From Date', ['class' => 'mb-2 fw-bold'])
                ->widget(DatePicker::classname(), [
                    'name' => 'dp_2',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'value' => '23-Feb-1982',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy'
                    ]
                ]);
            ?>
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-md-4">
            <?php
            echo $form
                ->field($model, 'deposit_to_date')
                ->label('Deposit To Date', ['class' => 'mb-2 fw-bold'])
                ->widget(DatePicker::classname(), [
                    'name' => 'dp_2',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'value' => '23-Feb-1982',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy'
                    ]
                ]);
            ?>
        </div>
        <!-- <div class="col-md-4">
            <?php
            /*
            echo $form
                ->field($model, 'narrative')
                ->label('Narrative', ['class' => 'mb-2 fw-bold'])
                ->textarea([
                    'rows' => 1,
                    'placeholder' => 'Enter Narrative'
                ]);
                */
            ?>
        </div> -->
        <div class="col-md-4">
            <?php
            echo $form
                ->field($model, 'transaction_no')
                ->label('Transaction No.', ['class' => 'mb-2 fw-bold'])
                ->textInput([
                    'placeholder' => 'Enter Transaction Number'
                ]);
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo $form
                ->field($model, 'deposit_amount')
                ->label('Deposit Amount', ['class' => 'mb-2 fw-bold'])
                ->textInput([
                    'placeholder' => 'Enter Deposit Amount'
                ]);
            ?>
        </div>
    </div>
    <div class="row mb-2">

        <div class="col-md-4">
            <?php
            echo $form
                ->field($model, 'post_amount')
                ->label('Posting Amount', ['class' => 'mb-2 fw-bold'])
                ->textInput([
                    'placeholder' => 'Enter Post Amount'
                ]);
            ?>
        </div>
        <div class="col-md-4" style="margin-top: 13px;">
            <?= Html::submitButton('Search', ['class' => 'btn btn-success  mt-3']) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php
$findBranch = Url::to(['/fees-management/receipt-sponsor-fund/find-branch']);





$findBranchJs = <<< JS
const findBranchUrl = '$findBranch'


document.getElementById('branch-name-element').disabled = true

const setBranchName = (branch) => {
    const select = document.getElementById('branch-name-element')
    select.disabled = false
    var option = document.createElement("option");
    option.value = branch.branch_code;
    option.innerHTML = branch.branch_name;
    select.appendChild(option)
    select.value =  branch.branch_code
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
