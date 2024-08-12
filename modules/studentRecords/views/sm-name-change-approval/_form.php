<?php
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
$request = Yii::$app->request;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sm-name-change-approval-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    <?//= $form->field($model, 'name_change_approval_id')->textInput() ?>-->

    <?= $form->field($model, 'name_change_id')->hiddenInput(['value'=>$request->get('name_change_id')])->label(false)  ?>

<!--    <?//= $form->field($model, 'approval_status')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'approval_status')->label('Approval Status',['class'=>'mb-2 fw-bold'])->widget(Select2::classname(), [
    'data' =>['APPROVED' => 'APPROVED', 'REVIEW' => 'REVIEW AND RE-SUBMIT', 'REJECTED'=>'REJECTED'],
    'language' => 'en',
    'options' => ['placeholder' => 'Select a status...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);?>

    <?= $form->field($model, 'remarks')->textarea(['rows' => '6','maxlength' => true]) ?>

    <?= $form->field($model, 'approved_by')->hiddenInput(['maxlength' => true, 'value'=>Yii::$app->user->getId()])->label(false)  ?>

    <?= $form->field($model, 'approval_date')->hiddenInput(['value'=>date("Y-m-d")])->label(false)  ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
