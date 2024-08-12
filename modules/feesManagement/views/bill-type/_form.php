<?php

use kartik\builder\Form;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\BillingType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="billing-type-form">

    <?php $form = ActiveForm::begin([
        'id' => 'billing-type-form',
        'enableClientValidation' => true, // Enable client-side validation
        'enableAjaxValidation' => false, // Set to true if you want to enable AJAX validation
    ]); ?>

    <?php
    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'compactGrid'=>true,
        'attributes'=>[
            'billing_type_desc'=>[
                'type'=>Form::INPUT_TEXT, 
                'options'=>['placeholder'=>'Enter Type Description...'],
                'label' => false,
            ],

            'actions'=>[
                'type'=>Form::INPUT_RAW, 
                'value'=>'<div style="text-align: right; margin-top: 20px">' . 
                    Html::resetButton('Reset', ['class'=>'btn btn-secondary btn-default']) . ' ' .
                    Html::submitButton(Yii::t('app', 'Submit'), ['class'=>'btn btn-primary']) . 
                    '</div>'
            ],
        ]
    ]);
    ?>

    <?php ActiveForm::end(); ?>

</div>
