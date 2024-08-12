<?php

use app\models\SmStudent;
use app\models\Student;
use app\modules\feesManagement\models\ReceiptSponsorFund;
use app\modules\feesManagement\models\search\ReceiptSponsorFundSearch;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SponsorBeneficiary $model */
/** @var yii\widgets\ActiveForm $form */
$receipt_sponsor_fund_id = Yii::$app->request->queryParams['receipt_sponsor_fund_id'];

$receiptSponsorFund = ReceiptSponsorFund::findOne($receipt_sponsor_fund_id);
$balance = $receiptSponsorFund->findBalance();


?>



<div class="sponsor-beneficiary-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'receipt_sponsor_fund_id')->hiddenInput(['value' => $receipt_sponsor_fund_id])->label(false) ?>
    <?= $form->field($model, 'transfer_status')->hiddenInput(['value' => 'PENDING'])->label(false) ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false)

    ?>
    <div class="mb-2">
        <?= $this->render('_info_card', ['receiptSponsorFund' => $receiptSponsorFund]); ?>

    </div>

    <div class="row">
        <div class="col">
            <div class=" mb-2">
                <div class="col-md-12">
                    <?php
                    $types = Student::find()->select(['student_number'])->asArray()->all();
                    $data = ArrayHelper::map($types, 'student_number', 'student_number');
                    echo $form
                        ->field($model, 'reg_no')
                        ->label('<span class=fw-bold mb-2>Registration Number</span>')
                        ->widget(Select2::classname(), [
                            'data' => $data,
                            'language' => 'en',
                            'options' => [
                                'placeholder' => 'Select Reg No ...',
                                'id' => 'student-regno-element',
                                'onchange' => 'js:studentNameToggle(this)',
                                'disabled' => $disabled ?? false,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="col mb-2">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'name')
                    ->textInput([
                        'maxinput' => true, 
                        'id' => 'student-name-element',
                         'readonly' => true, 
                         'class' => 'form-control',
                         'disabled' => $disabled ?? false,
                        ])
                    ->label('<span class=fw-bold mb-2>Student Name</span>')
                ?>

            </div>
        </div>

    </div>


    <div class="row mb-2">
        <div class="col">
            <div class="col-md-12">
                <?php
                $data = ['POST' => 'POST', 'CANCEL' => 'CANCEL'];
                echo $form
                    ->field($model, 'trans_type')
                    ->label('<span class=fw-bold mb-2>Trans Type</span>')
                    ->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'en',
                        'options' => [
                            'placeholder' => 'Select Trans Type ...',
                            'disabled' => $disabled ?? false,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
            </div>
        </div>

        <div class="col">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'amount')
                    ->textInput([
                        'maxinput' => true,
                        'id' => 'sponsor-beneficiary-amount',
                        'onchange' => 'js:checkAmount(this)',
                    ])
                    ->label('<span class=fw-bold mb-2>Amount</span>')
                ?>
            </div>

        </div>


    </div>

    <div class="row mb-2">

        <div class="col-md-6">
            <?=
            $form
                ->field($model, 'post_date', ['labelOptions' => ['class' => 'fw-bold']])
                ->label('<span class="fw-bold mb-2">Post Date</span>')
                ->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Select Post Date ...',
                        'disabled' => $disabled ?? false,

                ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]);
            ?>

        </div>
        <div class="col-md-6" style="margin-top: 12px">
            <div class="form-group">
                <?= Html::submitButton('Save Beneficiary', ['class' => 'btn btn-primary mt-3', 'id' => 'submit-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<?php
$findNameUrl = Url::to(['/fees-management/sponsor-beneficiary/find-name']);

$sponsorJs = <<< JS
const findNameUrl = "$findNameUrl"
const balance = "$balance"


const setName = (name) => {
    document.getElementById('student-name-element').value = name
}

document.getElementById('sponsor-beneficiary-amount').addEventListener('change', (e) => {
    e.target.value = parseInt(e.target.value).toFixed(2)
})


const studentNameToggle = async (e) => {
    const student_reg_no = e.value
    const response = await fetch(findNameUrl, 
        {method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({data:{
            student_reg_no:student_reg_no,
        }}),
    })
    const data = await response.json();
    setName(data.name)
}

    const getErrorElement = (element) => {
        const elementid = element.getAttribute('id')
        const errorElementId = elementid + 'Errors'
        let errorElement = this.document.getElementById(errorElementId) 

        if (errorElement) {
            errorElement.innerHTML= ''
        } else {
            errorElement = this.document.createElement('div')
            errorElement.setAttribute('id', errorElementId)
            errorElement.classList.add("text-danger")
        }
        return errorElement
    }

    const checkAmount = (element) => {

        if (parseInt(element.value) > parseInt(balance)) {
            const errorElement = getErrorElement(element)
            errorElement.style.fontSize = 'larger'

            const span = document.createElement('span');
            errorElement.appendChild(span)
            const msg = 'amount cannot exceed '+ balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
   
            const spanMsg = document.createElement('span').appendChild(document.createTextNode(msg))
            span.appendChild(spanMsg)
            element.after(errorElement)

        }
    }

JS;
$this->registerJs($sponsorJs, yii\web\View::POS_END);
