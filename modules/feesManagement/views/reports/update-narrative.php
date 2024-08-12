<?php

use app\helpers\SmisHelper;
use app\models\PaymentMode;
use app\modules\feesManagement\models\BankBranches;
use app\modules\feesManagement\models\Banks;
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
        <div class="card">
            <div class="card-header">
                <h5 class="text-center text-bold"><?= Html::encode($this->title) ?></h5>
            </div>
            <div>
                <div class="narrative-form px-4">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form->field($model, 'trans_id')->hiddenInput([
                            ])->label(false) ?>

                            <?= $form
                                ->field($model, 'account_no')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Account Number', ['class' => 'mb-2 fw-bold'])

                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'deposit_date')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Deposit Date', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'post_comment')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Narrative/Reference', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'deposit_amount')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Deposit Amount', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'reg_number')
                                ->textInput()
                                ->label('Correct Reg No', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?php
                            $types = PaymentMode::find()->select(['payment_mode_id', 'description'])->asArray()->all();
                            $data = ArrayHelper::map($types, 'payment_mode_id', 'description');
                            echo $form
                                ->field($model, 'pay_mode')
                                ->label('Payment Mode', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Payment Mode ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?php
                            $types = Banks::find()->select(['brank_id', 'bank_name'])->asArray()->all();
                            $data = ArrayHelper::map($types, 'brank_id', 'bank_name');
                            echo $form
                                ->field($model, 'bank_id')
                                ->label('Bank', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Bank ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?php
                            $types = BankBranches::find()->select(['branch_code', 'branch_name'])->asArray()->all();
                            $data = ArrayHelper::map($types, 'branch_code', 'branch_name');
                            echo $form
                                ->field($model, 'branch_code')
                                ->label('Branch', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Branch ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'chq_no')
                                ->textInput()
                                ->label('Cheque Number', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'drawer_name')
                                ->textInput()
                                ->label("Drawer's Name", ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'drawer_account')
                                ->textInput()
                                ->label("Drawer's Account Number", ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">

                                <?= Html::submitButton('Update Bank Reference', ['class' => 'btn btn-success mt-3', 'value' => 'update', 'name' => 'value']) ?>
                            </div>
                        </div>
                        <div class="col-md-6 d-grid gap-2 d-md-flex justify-content-md-end">
                            <div class="form-group">
                                <?= Html::a(
                                    'Cancel/Back to Banking Slips',
                                    Url::to([
                                        '/fees-management/banking-slips/index',
                                    ]),
                                    [
                                        'class' => 'btn btn-danger mt-3',

                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>



            </div>
        </div>
    </div>