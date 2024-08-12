<?php

/*
 * @Author: Jeff Rureri 
 * @Email: wahome4jeff@gmail.com
 * @Date: 2024-05-20 11:52:09 
 * @Last Modified by: Jeff Rureri
 * @Last Modified time: 2024-05-20 12:35:37
 * @Description: file:///home/user/Documents/smis/modules/feesManagement/views/reports/search_receipt.php
 */

use app\helpers\SmisHelper;
use app\modules\feesManagement\models\BankingSlips;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Fees Management' => Url::to(['/fees-management']),
    $title => null
]);

?>

<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="text-center text-bold">Select Receipt Number to view receipt</h5>
            </div>
            <div class="receipt-form px-4">
                <?php $form = ActiveForm::begin(
                    [
                        'action' => ['/fees-management/banking-slips/receipt'],
                        'method' => 'get',
                    ]
                ); ?>


                <div class="row mb-2">
                    <div class="col-md-8">
                        <?php
                        $sp = BankingSlips::find()->select(['trans_id', 'receipt_no'])->asArray()->all();
                        $data = ArrayHelper::map($sp, 'trans_id', 'receipt_no');
                        echo $form
                            ->field($model, 'trans_id')
                            ->label('Receipt Number', ['class' => 'mb-2 fw-bold'])
                            ->widget(Select2::classname(), [
                                'data' => $data,
                                'language' => 'en',
                                'options' => [
                                    'placeholder' => 'Select Receipt Number ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                        ?>
                    </div>
                    <div class="col-md-4" style="margin-top: 9px;">
                        <?= Html::submitButton('Search Receipt', ['class' => 'btn btn-success  mt-3']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>