<?php

use app\modules\feesManagement\models\Banks;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;
use yii\web\ServerErrorHttpException;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feesManagement\models\search\BankingSlipsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fee Statement';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Fees Statement Report', 'url' => ['/fees-management/reports/fee-statement']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row mb-3 ">
                    <h5 class="px-4">Name: </h5>
                    <h5 class="px-4">Reg Number: </h5>
                </div>
                <div class="d-flex flex-row mb-3 ">
                    <h5 class="px-4">Overral Status: </h5>
                    <h5 class="px-4">ACADEMIC YEAR STATUS: </h5>
                </div>
                <div class="d-flex flex-row mb-3 ">
                    <h5 class="px-4">Academic Year: </h5>
                    <h5 class="px-4">Billing Currency: </h5>
                </div>
            </div>
        </div>

        <?php
        $transIdCol = [
            'label' => 'Programme',
            'value' => function ($model) {
                return $model['trans_id'];
            }
        ];


        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            $transIdCol,


        ];

        $toolbar = [
            '{export}',
            '{toggleData}',
        ];

        try {
            echo GridView::widget([
                'id' => 'register-for-courses-grid',
                'dataProvider' => $dataProvider,
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
                'export' => [
                    'fontAwesome' => true,
                    'label' => 'Export Report'
                ],
                'panel' => [
                    'heading' => '<h5 class="text-center">Programme Admission Report</h5>'
                ],
                'persistResize' => false,
                'itemLabelSingle' => 'programme',
                'itemLabelPlural' => 'programmes',
            ]);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
        ?>
    </div>
</div>