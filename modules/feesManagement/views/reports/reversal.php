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
                            <?= $form
                                ->field($model, 'receipt_no')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Receipt Number', ['class' => 'mb-2 fw-bold'])
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= $form
                                ->field($model, 'account_no')
                                ->textInput([
                                    'readonly' => true,
                                ])
                                ->label('Account Number', ['class' => 'mb-2 fw-bold'])
                            ?>
                            <?= $form->field($model, 'reg_number')->hiddenInput([])->label(false) ?>

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
                            <?php
                            if ($model['post_status'] === 'REVERSED') {
                                $disabled = true;
                            } else {
                                $disabled = false;
                            }
                            if ($active) {
                                $value = 'YES';
                            }else {
                                $value = '';
                            }
                            $data = [
                                'YES' => 'Yes',
                                'NO' => 'No',
                            ];
                            echo $form
                                ->field($model, 'reversal')
                                ->label('Allow Reversal', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => [
                                        'placeholder' => 'Select Yes/No ...',
                                        'disabled' => $disabled,
                                        'value' => $value,

                                    ],
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
                            $data = [
                                'YES' => 'Yes',
                                'NO' => 'No',
                            ];
                            echo $form
                                ->field($model, 'duplicate')
                                ->label('Allow Duplicate', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Yes/No ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                            ?>

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-9">
                            <?php
                            $data = [
                                'Yes' => '1',
                                'No' => '2',
                            ];
                            echo $form
                                ->field($model, 'run_balance')
                                ->label('Reinstate Cancelled Receipt', ['class' => 'mb-2 fw-bold'])
                                ->widget(Select2::classname(), [
                                    'data' => $data,
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Receipt...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                            ?>

                        </div>
                        <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                            <div class="form-group">
                                <?= Html::submitButton('Reinstate Cancelled Receipt', ['class' => 'btn btn-secondary mt-4', 'value' => 'reinstate', 'name' => 'value']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <?php if ($active) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success mt-3', 'value' => 'update', 'name' => 'value']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?= Html::submitButton('Reverse Receipt', ['class' => 'btn btn-outline-success mt-3', 'value' => 'reverse', 'name' => 'value']) ?>

                            </div>
                            <div class="col-md-4 d-grid gap-2 d-md-flex justify-content-md-end">
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
                        <?php } else { ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success mt-3', 'value' => 'update', 'name' => 'value']) ?>
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
                        <?php } ?>


                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>