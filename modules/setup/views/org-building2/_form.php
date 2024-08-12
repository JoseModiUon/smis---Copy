<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrgStudyCentre;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\OrgBuilding $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-building-form">
<div class="row mb-2">
        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>
	</div>
</div>
<div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'building_code')->textInput(['maxlength' => true]) ?>
	</div>
</div>
<div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'building_name')->textInput(['maxlength' => true]) ?>
	</div>
</div>
<div class="row mb-2">
        <div class="col-md-12">
    <?= $form->field($model, 'floors')->textInput() ?>
	</div>
</div>
<div class="row mb-2">
        <div class="col-md-12">
    
	<?php
                $study_centre = OrgStudyCentre::find()->select(['study_centre_id', 'study_centre_name'])->asArray()->all();
$data = ArrayHelper::map($study_centre, 'study_centre_id', 'study_centre_name');
echo $form
    ->field($model, 'study_center')
    ->label('Study Centre', ['class' => 'mb-2 fw-bold'])
    ->widget(Select2::classname(), [
        'data' => $data,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Study Centre ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
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
