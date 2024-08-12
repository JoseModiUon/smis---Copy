<?php

use app\models\SmStudentSponsor;
use app\modules\feesManagement\models\BankingSlips as ModelsBankingSlips;
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

$this->title = 'Transfer for Posting';
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


?>
<div class="sponsor-beneficiary-index">

    <div class="card">
        <div class="card-body">
            <div class="row">

                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                    <div>Sponsor: <?= ucwords($sponsor_name) ?></div>
                    <div>Source Reference: <?= ($sourceRef) ?></div>

                </h3>

                <div class="col-md-12">
                    <?= $this->render('_search', ['model' => $searchModel]); ?>
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
                    '{toggleData}',
                    [
                        'content' =>
                        Html::button(
                            '<i class="fas fa-check"></i> Transfer',

                            [
                                'title' => 'Post ',
                                'id' => 'post-beneficiary-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                                'onclick' => 'js:postBeneficiary(this)'

                            ]
                        ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model) use ($assignedIds) {
                            $bool = in_array($model['sponsor_beneficiary_id'], $assignedIds);
                            return [
                                'checked' => $bool,
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
                        'label' => 'Status',
                        'value' => function ($model) {
                            $record = ModelsBankingSlips::find()->where(['sponsor_beneficiary_id' => $model->sponsor_beneficiary_id])->one();
                            if ($record) return $record->post_status;
                            return $model->transfer_status;
                        }
                    ],
                    'amount',
                    'post_date',
                    'user_id',
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php
$urlPostBeneficiary = Url::to(['/fees-management/receipt-sponsor-fund/post-beneficiary']);


$beneficiaryJs = <<< JS
let urlPostBeneficiary = '$urlPostBeneficiary';
const receiptId = "$receipt_id"


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
