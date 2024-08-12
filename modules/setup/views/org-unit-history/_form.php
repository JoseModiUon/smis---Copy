<?php
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrgUnit;
use app\models\OrgUnitTypes;
use app\models\OrgUnitHead;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrgUnitHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="org-unit-history-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    <?//= $form->field($model, 'org_unit_id')->textInput() ?>-->

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
            echo $form
                ->field($model, 'org_unit_id')
                ->label('Organisation Unit Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Organisation Unit Name ...'],
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
            $types = OrgUnitTypes::find()->select(['unit_type_id', 'unit_type_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_type_id', 'unit_type_name');
            echo $form
                ->field($model, 'org_type_id')
                ->label('Organisation Unit Type', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Organisation Unit Type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
<!--    <?//= $form->field($model, 'org_type_id')->textInput() ?>-->
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
            echo $form
                ->field($model, 'parent_id')
                ->label('Parent Organisation Unit', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Parent Organisation Unit ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
<!--    <?//= $form->field($model, 'parent_id')->textInput() ?>-->
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $types = OrgUnit::find()->select(['unit_id', 'unit_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_id', 'unit_name');
            echo $form
                ->field($model, 'successor_id')
                ->label('Successor Unit Name', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Successor Unit  ...'],
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
            $types = OrgUnitHead::find()->select(['unit_head_id', 'unit_head_name'])->asArray()->all();
            $data = ArrayHelper::map($types, 'unit_head_id', 'unit_head_name');
            echo $form
                ->field($model, 'unit_head_id')
                ->label('Unit Head', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Unit Head ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'start_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter end date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
            ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <?=
            $form
                ->field($model, 'end_date')
                ->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter end date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
            ?>
        </div>
    </div>



    <?= $form->field($model, 'user_id')->hiddenInput(['value' =>Yii::$app->user->id])->label(false) ?>

<!--    <?//= $form->field($model, 'date_created')->textInput() ?>-->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
