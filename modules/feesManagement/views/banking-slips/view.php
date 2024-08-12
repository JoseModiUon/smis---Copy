<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feesManagement\models\BankingSlips */
?>
<div class="banking-slips-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'trans_id',
            'deposit_date',
            'deposit_type',
            'deposit_amount',
            'reg_number',
            'other_names',
            'post_status',
            'post_comment',
            'account_no',
            'receipt_no',
            'process_date',
            'batch_no',
            'pay_mode',
            'chq_no',
            'drawer_account',
            'trans_reference',
            'branch_code',
            'run_balance',
            'last_update',
            'user_id',
            'drawer_name',
            'student_type',
            'source_reference',
            'registration_number',
            'value_date',
            'file_id',
            'sponsor_beneficiary_id',
            'bank_id',
            'bank_number',
        ],
    ]) ?>

</div>
