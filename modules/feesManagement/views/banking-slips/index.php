<?php

use app\helpers\SmisHelper;
use app\modules\feesManagement\models\Banks;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\feesManagement\models\search\BankingSlipsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Fees Management' => Url::to(['/fees-management']),
    $title => null

]);
?>
<div class="banking-slips-index">
    <div class="card">
        <div class="card-body">

            <div class="pb-4">
                <h3 class="card-title mb-3">
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>

            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->

                        <div class="modal-body container px-2">
                            <div>
                                <h5 class="text-center modal-title">Post Comments</h5>
                            </div>
                            <div class="mb-3">
                                <label for="post-comments-narration" class="form-label">Narration</label>
                                <textarea oninput="checkErros(this)" class="form-control" id="post-comments-narration" rows="3" required></textarea>
                                <div class="invalid-feedback" id="invalid-narration">
                                    Please provide narration
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between modal-footer">
                                <input type='submit' class='btn btn-info' value='Submit' id='post-fee-btn' onclick="postFees()">
                                <button type="button" id="modal-close-btn" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <?= $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'id' => 'banking-slips-table',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'striped' => true,
                'hover' => true,
                'condensed' => true,
                'responsive' => true,
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
                            '<i class="fas fa-check"></i> Post Fees',
                            [
                                'title' => 'Post Fees',
                                'id' => 'post-fees-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                                "data-bs-toggle" => "modal",
                                "data-bs-target" => "#myModal",
                                'onclick' => 'js:postFeesHelper(this)'

                            ]
                        ),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                ],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model) {
                            // $bool = in_array($model['sponsor_beneficiary_id'], $assignedIds);
                            return [
                                'checked' => $model['post_status'] == 'POSTED' ? true : false,
                                'value' => $model['trans_id'],
                            ];
                        }
                    ],
                    [
                        'attribute' => 'bank_id',
                        'label' => 'Bank Name',
                        'value' => function ($model) {
                            return Banks::findOne($model['bank_id'])?->getBankBranch()?->one()?->getBank()?->one()?->bank_name;
                        }
                    ],
                    [
                        'attribute' => 'bank_id',
                        'label' => 'Deposit Branch',
                        'value' => function ($model) {
                            return Banks::findOne($model['bank_id'])?->getBankBranch()?->one()->branch_name;
                        }
                    ],
                    // [
                    //     'attribute' => 'trans_reference',
                    //     'label' => 'Trans Reference',
                    // ],
                    // [
                    //     'attribute' => 'post_comment',
                    //     'label' => 'Narrative',
                    // ],
                    [
                        'attribute' => 'reg_number',
                        'label' => 'Reg No.',

                    ],
                    [
                        'attribute' => 'deposit_amount',
                        'label' => 'Amount',

                    ],
                    [
                        'attribute' => 'receipt_no',
                        'label' => 'Receipt No.',

                    ],
                    [
                        'attribute' => 'deposit_date',
                        'label' => 'Post Date',

                    ],
                    [
                        'attribute' => 'post_status',
                        'label' => 'Post Status',
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update}{view}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                if($model['post_status'] === 'POSTED'){
                                    return Html::a(
                                        '<i class="fa-solid fa-rotate"></i>',
                                        Url::to([
                                            '/fees-management/reports/reversal',
                                            'trans_id' => $model['trans_id'],
                                        ]),
                                        [
                                            'title' => 'Reverse/Duplicate the receipt',
                                            'class' => 'btn btn-link',
    
                                        ]
                                    );
                                    
                                }else{
                                    return Html::a(
                                        '',
                                        Url::to([
                                            '/fees-management/reports/update-narrative',
                                            'trans_id' => $model['trans_id'],
                                        ]),
                                        [
                                            'title' => 'Update narrative',
                                            'class' => 'btn btn-link bi bi-pencil',
    
                                        ]
                                    );
                                }
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a(
                                    '',
                                    Url::to([
                                        '/fees-management/banking-slips/receipt',
                                        'trans_id' => $model['trans_id'],
                                    ]),
                                    [
                                        'title' => 'View receipt',
                                        'class' => 'btn btn-link bi bi-eye',

                                    ]
                                );
                                
                            },
                        ]
                    ],


                ],

            ]) ?>
        </div>
    </div>
</div>

<?php
$postFeeUrl = Url::to(['/fees-management/banking-slips/post-fee-payments']);





$slipsJs = <<< JS

const postFeeUrl = "$postFeeUrl"

const checkErros = (e) => {
    const invalidDiv = document.getElementById('invalid-narration')
    invalidDiv.style.display = 'none'
    e.value = e.value.toUpperCase()

    if(e.value.length > 20){
        e.value = e.value.slice(0,20)
    }


}
const postFeesHelper = (e) => {
    const element = document.getElementById('post-comments-narration')
    const modal = document.getElementById('myModal')

    if(element.value) {
        modal.style.display = 'none'
        postFees()
    }
}
function getSelectedIds(){
    let keys = $('#banking-slips-table').yiiGridView('getSelectedRows');
    
    let ids = [];

    $('table > tbody').find('tr').each(function(e) {
        let dataKey = $(this).attr('data-key');
        
        if(keys.includes(parseInt(dataKey))){
            ids.push($(this).find('.kv-row-checkbox').val());
        }
    });
    
    return [...new Set(ids)]
}

const postFees = async (e) => {

    const ids = getSelectedIds()
    const element = document.getElementById('post-comments-narration')
    const invalidDiv = document.getElementById('invalid-narration')
    const narration = element.value
    
    if(narration == '') {
        element.classList.add("border")
        element.classList.add("border-danger")
        invalidDiv.style.display = 'block'
    } else {
        document.getElementById('modal-close-btn').click()

        if(ids.length == 0) {
            krajeeDialog.alert('Please select a record to post')
        } else {
            krajeeDialog.confirm('Are you sure you want to submit these beneficiaries?',  async (result) => {
                if (result) {
                    const response = await fetch(postFeeUrl, {method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({data:{
                            trans_ids:ids,
                            narration:narration,
                        }}),
                    })
                    const dataJson =  await response.json()
                    element.value = ''
                    krajeeDialog.alert(dataJson.msg);
                }
            });
        }
    }



}

JS;
$this->registerJs($slipsJs, yii\web\View::POS_END);
