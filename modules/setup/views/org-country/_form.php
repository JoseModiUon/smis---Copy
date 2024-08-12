<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\OrgCountry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country_code')->textInput(['maxlength' => true])->label('Country Code',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'country_name')->textInput(['maxlength' => true])->label('Country Name',['class'=>'mb-2 fw-bold']) ?>



    <?php
    echo $form->field($model, 'continent')->label('Continent',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
        'data' =>['Africa' => 'Africa','Antarctica' => 'Antarctica','Asia' => 'Asia','Europe' => 'Europe','North America'=>'North America', 'Oceania' => 'Oceania',
            'South America' => 'South America',],
        'language' => 'en',
        'options' => ['placeholder' => 'Select Continent...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>

    <?= $form->field($model, 'region')->textInput(['maxlength' => true])->label('Region',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'code2')->textInput(['maxlength' => true])->label('Country Code 2',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'nationality')->textInput(['maxlength' => true])->label('Nationality',['class'=>'mb-2 fw-bold']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
