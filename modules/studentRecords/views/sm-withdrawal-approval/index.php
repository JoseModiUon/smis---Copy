<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use kartik\detail\DetailView;
use app\models\SmWithdrawalRequest;
use app\models\SmWithdrawalApproval;

/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalApprovalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Withdrawal Approvals';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = $this->title;
$request = SmWithdrawalRequest::findOne(Yii::$app->request->get('withdrawal_request_id'));


?>
<div class="sm-withdrawal-approval-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>

            <div class="row mb-2">
                <div class="col-md-5">
                    <p class="fw-bold">Withdrawal Type: <?=$request->getSmWithdrawalType()->one()->withdrawal_type_name?></p>
                    <p class="fw-bold"><?=$request->getStudent()->one()->getFullNames()?>: <?=$request->getStudent()->one()->student_number?></p>
                    
                    <p class="fw-bold">
                        Supporting Document:
                        <span class="pl-2">
                            <?php
                                if($request->supporting_doc_url):
                                    $student = $request->getStudent()->one();
                                    $reg_no=str_replace('/', '_', $student->student_number);
                                    echo Html::a('', ['/student-records/sm-withdrawal-request/download','document_url' => $request->supporting_doc_url,'file'=>$reg_no.'.pdf'], ['class' => ' bi bi-download']);
                                endif;
                            ?>
                        </span>

                    </p>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-12">
                    <?=
       GridView::widget([
       'dataProvider' => $dataProvider,
       'filterModel' => $searchModel,
       'export' => false,
       'columns' => [
           ['class' => 'kartik\grid\SerialColumn'],


           [
            'attribute' => 'level',
            'label' => 'Approval Level',
            'value' => function ($model) {
                return $model->approvals->level;
            }
            ],
           [
            'attribute' => 'approver',
            'label' => 'Approver',
            'value' => function ($model) {
                return $model->approvals->approver;
            }
        ],
        //    'approver_id',
           'comments',
           'approval_status',
       ],
       ]);
?>
                </div>
            </div>





        </div>
    </div>
</div>
