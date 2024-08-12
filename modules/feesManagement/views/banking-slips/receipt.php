<?php
/*
* @Author: Jeff Rureri
* @Email: wahome4jeff@gmail.com
* @Date: 2024-05-17 11:01:31
 * @Last Modified by: Jeff Rureri
* @Last Modified time: 2024-05-20 12:51:47
* @Description: file:///home/user/Documents/smis/modules/feesManagement/views/banking-slips/receipt.php
*/


use app\helpers\SmisHelper;
use app\models\PaymentMode;
use app\modules\feesManagement\models\BankBranches;
use app\modules\feesManagement\models\BankingSlips;
use app\modules\feesManagement\models\Banks;
use app\modules\studentRegistration\models\StudentProgCurriculum;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\feesManagement\models\BankingSlips */
/* @var $form yii\widgets\ActiveForm */

$this->title = $title;

echo SmisHelper::createBreadcrumb([
    'Fees Management' => Url::to(['/fees-management']),
    'Banking Slips' => Url::to(['/fees-management/banking-slips']),
    $title => null

]);
?>

<div class="section">
    <div class="container">
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary" id="createPdf"><i class="fa-solid fa-print"></i> Print Receipt</button>
        </div>
        <div id="receipt-template">
            <div class="card w-50">
                
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="row">
                                <p><strong>Receipt Number: </strong><?php echo $model->receipt_no; ?></p>
                            </div>
                            <div class="row">

                            </div>
                            <div class="row">
                                <p><strong>Date: </strong><?= date('d-M-Y'); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row">
                                <p><strong>Registration Number: </strong><?php echo $model->reg_number; ?></p>
                            </div>
                            <div class=" row">
                                <p><strong>Programme: </strong>
                                    <?= BankingSlips::findOne($model->trans_id)->getStudent()->one()
                                        ->getStudentProgrammeCurriculums()->one()->getProgCurriculum()->one()
                                        ->prog_curriculum_desc; ?>
                                </p>
                            </div>
                            <div class="row">
                                <p><strong>Full Names: </strong>
                                    <?= BankingSlips::findOne($model->trans_id)->getStudent()->one()
                                        ->other_names; ?>
                                </p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="row">
                                <p><strong>Academic Year: </strong>
                                    <?= BankingSlips::findOne($model->trans_id)->getStudent()->one()
                                        ->getStudentProgrammeCurriculums()->one()->getSmAcademicProgresses()->one()
                                        ->getAcadSession()->one()->acad_session_name ?></p>
                            </div>
                            <div class=" row">

                            </div>
                            <div class="row">
                                <p><strong>Level of Study: </strong>
                                    <?= BankingSlips::findOne($model->trans_id)->getStudent()->one()
                                        ->getStudentProgrammeCurriculums()->one()->getSmAcademicProgresses()->one()->getAcademicLevel()->one()->academic_level_name; ?>
                                </p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <p><strong>The Sum of: </strong><?php echo $model->deposit_amount ?></p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <table class="table table-bordered text-center" id="receipt-table">
                                    <tbody>
                                        <tr>
                                            <th>
                                                Being Payment of
                                            </th>
                                            <th>
                                                Amount
                                            </th>
                                        </tr>
                                        <tr>
                                            <td><?= BankingSlips::findOne($model->trans_id)->getFeeTransactions()->one()->trans_desc; ?></td>
                                            <td><?= BankingSlips::findOne($model->trans_id)->getFeeTransactions()->one()->trans_amount; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-bold d-grid d-md-flex justify-content-md-center">Total Amount Ksh. </p>
                                            </td>
                                            <td><?php echo $model->deposit_amount ?></td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="row ">
                                <div class="row">
                                    <p><strong>Payment Mode: </strong>
                                        <?= BankingSlips::findOne($model->trans_id)->getPaymentModes()->one()->description ?></p>
                                </div>
                                <div class=" row">
                                    <p><strong>Cheque Details: </strong>
                                        <?php echo $model->chq_no ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p><strong>Drawer: </strong>
                                        <?php echo $model->drawer_account ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="row ">
                                <div class="row">
                                    <p><strong>Bank: </strong>
                                        <?= Banks::findOne($model['bank_id'])?->getBankBranch()?->one()?->getBank()?->one()?->bank_name; ?></p>
                                </div>
                                <div class=" row">
                                    <p><strong>Branch: </strong>
                                        <?= Banks::findOne($model['bank_id'])?->getBankBranch()?->one()->branch_name; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p><strong>Cheque Number: </strong>
                                        <?php echo $model->chq_no ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="row ">
                                <div class="row">
                                    <p><strong>Collection Point: </strong>
                                        STUDENT FINANCE
                                    </p>
                                </div>
                                <div class=" row">
                                    <p><strong>Posted By: </strong>
                                        <?php echo Yii::$app->user->identity->username; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p><strong>Outstanding Balance: </strong>
                                        <?php echo $model->deposit_amount ?></td>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$receiptJs = <<< JS

document.getElementById('createPdf').addEventListener('click', function() {
    const element = document.getElementById('receipt-template'); // Get the HTML element to be converted to PDF
    html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png'); // Convert canvas to image data
    const pdf = new jsPDF(); // Initialize jsPDF
    const imgProps = pdf.getImageProperties(imgData);
    console.log(imgProps);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    console.log(pdfWidth);
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
    console.log(pdfHeight);
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight); // Add image to PDF
    pdf.save('receipt.pdf'); // Save PDF
});
});

JS;
$this->registerJs($receiptJs, yii\web\View::POS_END);
