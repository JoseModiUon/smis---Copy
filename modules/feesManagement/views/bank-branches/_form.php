<?php

use app\modules\feesManagement\models\Banks;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\BankBranches $model */
/** @var yii\widgets\ActiveForm $form */


// Fetch all bank codes
$allBankCodes = \yii\helpers\ArrayHelper::map(app\modules\feesManagement\models\Banks::find()->all(), 'bank_code', 'bank_code');
// Fetch bank codes from model (if available)
$modelBankCodes = $model->bank_code ? [$model->bank_code] : [];

// Merge both sets of bank codes
$bankCodes = array_merge($modelBankCodes, $allBankCodes);

// Remove duplicate bank codes
$bankCodes = array_unique($bankCodes);
// Re-index the array to start from 0
$bankCodes = array_values($bankCodes);
?>

<div class="bank-branches-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'bank_code')->dropDownList(
        ArrayHelper::map(Banks::find()->all(), 'bank_code', 'bank_code'),
    )
    ?>

    <?= $form->field($model, 'bank_code')->widget(\kartik\select2\Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map($bankCodes, function ($element) {
            return $element;
        }, function ($element) {
            return $element;
        }),
        'language' => 'en',
        'options' => ['placeholder' => 'Select bank code ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>





    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>