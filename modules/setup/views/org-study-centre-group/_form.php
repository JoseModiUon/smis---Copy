<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgStudyCentre;
use app\models\OrgStudyGroup;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OrgStudyCentreGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-study-centre-group-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgStudyCentre::find()->select(['study_centre_id', 'study_centre_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'study_centre_id', 'study_centre_name');
            echo $form
                ->field($model, 'study_centre_id')
                ->label('Study Centre Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Study Centre Name ...'],
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
            $types = OrgStudyGroup::find()->select(['study_group_id', 'study_group_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'study_group_id', 'study_group_name');
            echo $form
                ->field($model, 'study_group_id')
                ->label('Study Centre Group', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Study Group  ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>


<!--    <?//= $form->field($model, 'study_group_id')->textInput() ?>-->
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
