<?php

use app\modules\feesManagement\models\Banks;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feesManagement\models\search\BankingSlipsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'STATEMENT OF FEES ACCOUNT';
$this->params['breadcrumbs'][] = ['label' => 'Fees Management', 'url' => ['/fees-management']];
$this->params['breadcrumbs'][] = ['label' => 'Fees Statement Report', 'url' => ['/fees-management/reports/fee-statement']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center text-bold">
                    University of Nairobi
                </h3>

            </div>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary" id="createPdf">Print Statement</button>
            </div>




            <div class="mt-3">
                <div class="row">
                    <table class="table table-bordered" id="statement-table">
                        <tbody>
                            <?php if (empty($data)) : ?>
                                <tr>
                                    <td>No Records</td>
                                </tr>
                            <?php endif; ?>



                            <?php foreach ($data as $year => $row) : ?>

                                <?php if ($year === array_key_first($data)) : ?>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="mb-2">Date <span style="font-style: italic;"><?= date('d-M-Y'); ?></span></div>
                                                <div class="mb-2">Name: <?= $row['0']['student_names'] ?></div>
                                            </div>
                                            <div>
                                                <div class="mb-2">Reg No: <?= $row['0']['registration_number'] ?></div>
                                                <div>Overral Status: <?= 'CURRENT' ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between">
                                                <div style="margin-left: -5px;">ACADEMIC YEAR STATUS: <?= 'CURRENT' ?></div>

                                            </div>
                                        </div>
                                    </div>


                                <?php endif; ?>

                                <tr>
                                    <td colspan="7">
                                        <span class="font-weight-bold">Academic Year:</span>
                                        <?= $year ?>
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        <span class="font-weight-bold">Currency:</span>
                                        <?= ArrayHelper::getValue($row, '0.currency_id') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <?php if (array_key_exists('openingBalance', $row)) : ?>
                                        <td></td>
                                        <td colspan="6" class="font-weight-bolder">Opening Balance: <?= $row['openingBalance'] ?></td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <th scope="col">Transaction</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Debits DR</th>
                                    <th scope="col">Credits CR</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Cur.Rate</th>
                                </tr>
                                <?php foreach ($row as $item) :
                                    if (!is_array($item)) {
                                        continue;
                                    }
                                    $runningBalance = 0;
                                    $credits = $item['credits'];
                                    $debits = $item['debits'];
                                    $runningBalance = $runningBalance + $debits - $credits;
                                    if ($item['exchange_rate'] == 1) {
                                        $currency = "KES=" . $item['exchange_rate'];
                                    } else {
                                        $currency = "USD=" . $item['exchange_rate'];
                                    }
                                ?>
                                    <tr>
                                        <td><?= $item['trans_id'] ?></td>
                                        <td><?= $item['trans_date'] ?></td>
                                        <td><?= $item['trans_desc'] ?></td>
                                        <td><?= number_format($debits, 2) ?></td>
                                        <td><?= number_format($credits, 2) ?></td>
                                        <td><?= number_format($runningBalance, 2) ?></td>
                                        <td><?= $currency ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="font-weight-bold">
                                    <td colspan="3">Academic Year Totals :</td>
                                    <td><?= number_format($row['totalDebits'],2) ?></td>
                                    <td><?= number_format($row['totalCredits'],2) ?></td>
                                    <td><?= number_format($row['balance'],2) ?></td>
                                    <td></td>
                                </tr>
                                <tr class="font-weight-bold text-right">
                                    <td colspan="7"> Closing Balance :
                                        <?= number_format($row['balance'],2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


<?php
$postFeeUrl = Url::to(['/fees-management/banking-slips/post-fee-payments']);





$feeStamentJs = <<< JS

// const jsPDF = require('jspdf')

const postFeeUrl = "$postFeeUrl"



document.getElementById('createPdf').addEventListener('click', function() {
    const element = document.getElementById('statement-table'); // Get the HTML element to be converted to PDF
    html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png'); // Convert canvas to image data
    const pdf = new jsPDF(); // Initialize jsPDF
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight); // Add image to PDF
    pdf.save('converted-document.pdf'); // Save PDF
});
});

JS;
$this->registerJs($feeStamentJs, yii\web\View::POS_END);
