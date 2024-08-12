<?php

use yii\helpers\Html;
use app\models\SmApprover;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-approval-form">

    <?php $form = ActiveForm::begin(); ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php
        $withdrawal_request_id = Yii::$app->request->get('withdrawal_request_id');
echo $form->field($model, 'withdrawal_request_id')->hiddenInput(['value' => $withdrawal_request_id])->label(false)
?>

    <?php
    $username = Yii::$app->user->identity->username;
$approver = SmApprover::find()->where(['approver' => $username,'status' => 'ACTIVE'])->one();
// echo $form->field($model, 'approver_id')->hiddenInput(['value' => $approver->approver_id])->label(false)
echo $form->field($model, 'approver_id')->hiddenInput(['value' => 1])->label(false)
?>
    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
            ->field($model, 'comments')
            ->label('Comments', ['class' => 'mb-2 fw-bold'])
            ->textarea(['rows' => 3])
?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php 
        $withdrawal_request_id = Yii::$app->request->get('withdrawal_request_id');
        echo $form->field($model, 'withdrawal_request_id')->hiddenInput(['value' => $withdrawal_request_id])->label(false)
    ?>

    <?php 
        $username = Yii::$app->user->identity->username;
        $approver = SmApprover::find()->where(['approver' => $username,'status' => 'ACTIVE'])->one();
        // echo $form->field($model, 'approver_id')->hiddenInput(['value' => $approver->approver_id])->label(false)
        echo $form->field($model, 'approver_id')->hiddenInput(['value' => 1])->label(false)
    ?>
    <div class="row mb-2">
        <div class="col-md-12">
            <?=
                $form
                ->field($model, 'comments')
                ->label('Comments', ['class'=>'mb-2 fw-bold'])
                ->textarea(['rows' => 3]) 
            ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
<<<<<<< HEAD
<<<<<<< HEAD
            <?=
    $form
    ->field($model, 'approval_status')
    ->label('Approval Status', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => ['APPROVED' => 'APPROVED', 'REJECTED' => 'REJECTED'],
        'language' => 'en',
        'options' => ['placeholder' => 'Select approval status...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
            <?= 
                $form
                ->field($model, 'approval_status')
                ->label('Approval Status',['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' =>['APPROVED' => 'APPROVED', 'REJECTED' => 'REJECTED'],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select approval status...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        </div>
    </div> 
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
