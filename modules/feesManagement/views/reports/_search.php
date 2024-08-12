<?php

use app\models\ReceiptSponsorFund;
use app\models\SmStudentSponsor;
use app\models\Student;
use Yii;
use yii\helpers\Url;


use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\feesManagement\models\BankAccounts;
use app\modules\feesManagement\models\PaymentModes;
use app\modules\feesManagement\models\PaymentTypes;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrgProgCurrCourseSearch */
/* @var $form yii\widgets\ActiveForm */



?>



<div class="org-prog-curr-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view-statement'],
        'method' => 'get',
    ]); ?>


    <div class="row mb-2">
        <div class="col-md-4">
            <?php
            $sp = Student::find()->select(['student_id', 'student_number'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'student_id', 'student_number');
            echo $form
                ->field($model, 'student')
                ->label('Registration Number', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => [
                        'placeholder' => 'Select Registration Number ...',
                        // 'onchange' => 'js:toggleFund(this)',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-md-4" style="margin-top: 9px;">
            <?= Html::submitButton('Search', ['class' => 'btn btn-success  mt-3']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>