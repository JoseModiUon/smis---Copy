<?php

use app\models\OrgAcademicSession;
use app\models\ReceiptSponsorFund;
use app\models\SmStudentSponsor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\ReceiptSponsorFundSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Receipt Sponsor Funds';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Student Sponsors', 'url' => ['/student-records/sm-student-sponsor']];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;

$get = $request->get('sponsor_id');
?>
<div class="receipt-sponsor-fund-index">

    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-3">
                <?= Html::encode($this->title) ?>
            </h3>


            <div class="d-flex justify-content-end" style="margin-top: 50px;">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create Receipt',
                    ['create', 'sponsor_id' => $get],
                    ['class' => 'btn btn-lg btn-primary'],
                )
                ?>
            </div>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Sponsor',
                        'attribute' => 'sponsor_id',
                        'value' => function ($model) {
                            return SmStudentSponsor::findOne($model->sponsor_id)->sponsor_name;
                        },
                        'group' => true,
                        'groupedRow' => true,
                    ],

                    [
                        'attribute' =>
                        'source_reference',
                        'group' => true,
                        'subGroupOf' => 1
                    ],
                    [
                        'attribute' =>
                        'description',
                    ],
                    [
                        'label' => 'Credited Amount',
                        'attribute' => 'amount',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'label' => 'Balance',
                        // 'attribute' => 'balance',
                        'format' => ['decimal', 2],
                        'value' => function ($model) {
                            return $model->getBalance();
                        },
                    ],
                    [
                        'attribute' =>
                        'trans_type',
                    ],
                    [
                        'attribute' =>
                        'cheque_no',
                    ],
                    //'pv_no',
                    [
                        'label' => 'Academic Session',
                        'attribute' => 'academic_session',
                        'value' => function ($model) {
                            return OrgAcademicSession::findOne($model->academic_session)->acad_session_name;
                        },
                    ],

                    [
                        'attribute' => 'user_id',
                    ],

                    //'post_date',
                    // 'no_of_beneficiaries',
                    [
                        'attribute' =>
                        'no_of_beneficiaries',
                    ],
                    [
                        'label' => 'Allocated Beneficiaries',
                        'attribute' => 'allocated_beneficiaries',
                        'value' => function ($model) {
                            return $model->countBeneficiaries();
                        },
                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{view}{update}{upload}{beneficiary}',
                        'contentOptions' => [
                            'style' => 'white-space:nowrap',
                            'class' => 'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' View Beneficiaries ',
                                    [
                                        '/student-records/receipt-sponsor-fund/beneficiaries',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                        'sponsor_id' => Yii::$app->request->get('sponsor_id')
                                    ],
                                    [
                                        'class' => ' bi btn-link bi bi-eye text-primary',
                                    ],
                                );
                            },

                            'update' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Update ',
                                    [
                                        '/student-records/receipt-sponsor-fund/update',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                        'sponsor_id' => Yii::$app->request->get('sponsor_id')
                                    ],
                                    [
                                        'class' => ' bi btn-link bi bi-pencil-square text-success',
                                    ],
                                );
                            },

                            'upload' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Upload ',
                                    [
                                        '/student-records/receipt-sponsor-fund/upload-beneficiaries',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                        'sponsor_id' => Yii::$app->request->get('sponsor_id')
                                    ],
                                    [
                                        'class' => ' bi btn-link bi bi-cloud-arrow-up text-alert',
                                    ],
                                );
                            },

                            'beneficiary' => function ($url, $model, $key) {
                                return  Html::a(
                                    ' Beneficiary ',
                                    [
                                        '/student-records/sponsor-beneficiary/create',
                                        'receipt_sponsor_fund_id' => $model->receipt_sponsor_fund_id,
                                    ],
                                    [
                                        'class' => ' bi btn-link bi-person-plus-fill text-alert',
                                    ],
                                );
                            },
                        ],

                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>