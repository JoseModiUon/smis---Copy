<?php

use app\models\SmStudentSponsor;
use app\modules\feesManagement\models\BankingSlips as ModelsBankingSlips;
use app\modules\feesManagement\models\ReceiptSponsorFund;
use kartik\dialog\Dialog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\search\SponsorBeneficiary $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$sponsor_id = Yii::$app->session->get('sponsor_id');
$sponsor_name = SmStudentSponsor::findOne($sponsor_id)->sponsor_name;

$this->title = 'View Beneficiary';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsor', 'url' => ['/fees-management/sm-student-sponsor']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt Sponsor Funds', 'url' => ['/fees-management/receipt-sponsor-fund', 'sponsor_id' => $sponsor_id]];

$this->params['breadcrumbs'][] = $this->title;


echo Dialog::widget([
    'libName' => 'krajeeDialogCust',
    'options' => ['draggable' => true, 'closable' => true], // custom options
]);

$get = Yii::$app->request->get();

$receipt_id = '';
if (array_key_exists('SponsorBeneficiarySearch', $get)) {
    $receipt_id = $get['SponsorBeneficiarySearch']['receipt_sponsor_fund_id'];
} else {
    $receipt_id = $get['receipt_sponsor_fund_id'];
}

$savedIds = ModelsBankingSlips::find()->innerJoinWith('beneficiary')->select(['fss_sponsor_beneficiary.sponsor_beneficiary_id'])->where(['receipt_sponsor_fund_id' => $receipt_id])->all();
$assignedIds = ArrayHelper::getColumn($savedIds, 'sponsor_beneficiary_id');
$receiptSponsorFund = ReceiptSponsorFund::findOne($receipt_id);


?>
<div class="sponsor-beneficiary-index">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex flex-column">
                        <h3 class="card-title mb-3">
                            <?= Html::encode($this->title) ?>
                            <div>Sponsor: <?= ucwords($sponsor_name) ?></div>
                            

                        </h3>
                        <div class="mb-3">
                            <?= $this->render('_info_card', ['receiptSponsorFund' => $receiptSponsorFund]); ?>

                        </div>
                    </div>

                </div>
            </div>


            <?= GridView::widget([
                'id' => 'list-beneficiary-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
                'toolbar' => [
                    [
                        'content' =>
                        Html::a(
                            '<i class="bi bi-plus-lg"></i> Create Beneficiary',
                            Url::to(['/fees-management/sponsor-beneficiary/create', 'receipt_sponsor_fund_id' => $receipt_id]),
                            [
                                'title' => 'Add Sponsor Beneficiary',
                                'class' => 'btn btn-primary btn-spacer btn-sm',
                            ]
                        ) . '&nbsp' .
                            Html::button('<i class="bi bi-trash-fill"></i> Remove Beneficiary', [
                                'title' => 'Remove Beneficiary',
                                'id' => 'rmv-beneficiary-btn',
                                'class' => 'btn btn-danger btn-spacer btn-sm',
                                'onclick' => 'js:removeBeneficiary(this)'
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{toggleData}',

                ],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model) use ($assignedIds) {
                            $bool = in_array($model['sponsor_beneficiary_id'], $assignedIds);
                            return [
                                // 'checked' => $bool,
                                'value' => $model['sponsor_beneficiary_id'],
                                'disabled' => $bool

                            ];
                        }
                    ],
                    // 'sponsor_beneficiary_id',
                    // 'receipt_sponsor_fund_id',
                    'reg_no',
                    'name',
                    'trans_type',
                    [
                        'attribute' => 'transfer_status',
                        'label' => 'Transfer Status',
                        'value' => function ($model) {
                            $record = ModelsBankingSlips::find()->where(['sponsor_beneficiary_id' => $model->sponsor_beneficiary_id])->one();
                            if ($record) return 'TRANSFERRED';
                            return 'NOT TRANSFERRED';
                        }
                    ],
                    [
                        'attribute' => 'transfer_status',
                        'label' => 'Post Status',
                        'value' => function ($model) {
                            $record = ModelsBankingSlips::find()->where(['sponsor_beneficiary_id' => $model->sponsor_beneficiary_id])->one();
                            if ($record) return $record->post_status;
                            return $model->transfer_status;
                        }
                    ],
                    [
                        'attribute' => 'receipt_no',
                        'label' => 'Receipt No',
                        'value' => function ($model) {
                            $record = ModelsBankingSlips::find()->where(['sponsor_beneficiary_id' => $model->sponsor_beneficiary_id])->one();
                            if ($record) return $record->receipt_no;
                            return 'N/A';
                        }
                    ],
                    [
                        'attribute' => 'amount',
                        'label' => 'Amount',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDecimal($model->amount, 2);
                        }
                    ],
                    'post_date',
                    'user_id',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) use ($receipt_id, $assignedIds) {
                                $disabled = in_array($model['sponsor_beneficiary_id'], $assignedIds);
                                $class = ['class' => 'bi bi-pencil btn-link text-primary '];
                                if ($disabled) {
                                    $class = ['class' => 'btn disabled bi bi-pencil btn-link text-primary '];
                                }

                                return  Html::a(
                                    ' Update ',
                                    [
                                        '/fees-management/sponsor-beneficiary/update-beneficiary',
                                        'sponsor_beneficiary_id' => $model->sponsor_beneficiary_id,
                                        'receipt_sponsor_fund_id' => $receipt_id,
                                    ],
                                    $class
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
$urlPostBeneficiary = Url::to(['/fees-management/receipt-sponsor-fund/post-beneficiary']);
$urlRemoveBeneficiary = Url::to(['/fees-management/sponsor-beneficiary/remove-beneficiary']);



$beneficiaryJs = <<< JS
let urlPostBeneficiary = '$urlPostBeneficiary';
let urlRemoveBeneficiary = '$urlRemoveBeneficiary';
const receiptId = "$receipt_id"

const removeBeneficiary = (e) => {

    const ids = getSelectedIds()
    if(ids.length === 0){
        krajeeDialog.alert('No beneficiary were selected.');
    }else{
        const  data  = {
            receipt_sponsor_fund_id:receiptId,
            sponsor_beneficiary_ids:ids
        }
        krajeeDialog.confirm('Confirm Remove Beneficiaries?',  async (result) => {
            if(result) {
                const options = { 
                    method: 'post',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }, 
                    body: JSON.stringify(data) 
                }
                try {
                    const resp = await fetch(urlRemoveBeneficiary,options)
                    const dataJson =  await resp.json()
                    krajeeDialog.alert(dataJson.msg);        
                    location.reload()        
                } catch (error) {
                    krajeeDialog.alert(error);                
                
                }
            }
        })
    }

}
function getSelectedIds(){
    let keys = $('#list-beneficiary-grid').yiiGridView('getSelectedRows');
    
    let ids = [];

    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');
        
        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    
    return [...new Set(ids)]
}

const formData = (data)  => {
    const item = {}

    for (const row of data) {
        item[row[0]] = row[1]
    }

    const concatData = Array.of(item)
    
    const formData = new FormData();

    for (const key in concatData[0]) {
        formData.append(key,concatData[0][key]);
    }

    return formData
}

const findFormData = (attributeName = '') => {
    const target = document.forms[0]
    const formData = new FormData(target);

    if (attributeName) {
        let data = [...formData.entries()].filter(arr => {
            return arr.includes(attributeName)
        })
        return data.map(arr => Object.fromEntries([arr]))
    } else {
        return  [...formData.entries()]
    }
}

const redirect = () => {
    window.location = '/fees-management/receipt-sponsor-fund/list-beneficiary-view?receipt_sponsor_fund_id=' + receiptId;
}

const postBeneficiary = async () => {

    let selectedBeneficiaries = getSelectedIds()

    if(selectedBeneficiaries.length === 0){
        krajeeDialog.alert('No beneficiary were selected.');
    }else{
        krajeeDialog.confirm('Approve selected beneficiaries?',  async (result) => {
            if (result) {
                // $('#app-is-loading-modal').modal('show');
                const data = findFormData()
                data.push(['selectedBeneficiaries',selectedBeneficiaries])
                const options = { method: 'post', body: formData(data) }
                const resp = await fetch(urlPostBeneficiary,options)
                const dataJson =  await resp.json()
                // krajeeDialog.alert(dataJson.msg);
                redirect()
            }
        }); 
    }
}

JS;
$this->registerJs($beneficiaryJs, yii\web\View::POS_END);
