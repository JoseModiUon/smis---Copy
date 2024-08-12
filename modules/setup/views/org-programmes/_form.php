<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\OrgProgCategory;
use app\models\OrgProgType;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OrgProgrammes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-programmes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prog_code')->textInput(['maxlength' => true])->label('Programme Code',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'prog_short_name')->textInput(['maxlength' => true])->label('Short Name',['class'=>'mb-2 fw-bold']) ?>

    <?= $form->field($model, 'prog_full_name')->textInput(['maxlength' => true])->label('Full Name',['class'=>'mb-2 fw-bold']) ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgProgType::find()->select(['prog_type_id', 'prog_type_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'prog_type_id', 'prog_type_name');
            echo $form
                ->field($model, 'prog_type_id')
                ->label('Type ', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Type ...'],
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
            $types = OrgProgCategory::find()->select(['prog_cat_id', 'prog_cat_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'prog_cat_id', 'prog_cat_name');
            echo $form
                ->field($model, 'prog_cat_id')
                ->label('Category', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Programme Category...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

   <?= $form->field($model, 'status')->label('Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
    'data' =>['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],
    'language' => 'en',
    'options' => ['placeholder' => 'Select a status...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
