<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SmStudentSponsor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-student-sponsor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'sponsor_id')->textInput() 
    ?>

    <?= $form->field($model, 'sponsor_code')->textInput([
        'maxlength' => true,
        'oninput' => 'js:autoCapitalize(this)',

    ]) ?>

    <?= $form->field($model, 'sponsor_name')->textInput([
        'maxlength' => true,
        'oninput' => 'js:autoCapitalize(this)',

    ]) ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $data = ['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'];
            echo $form
                ->field($model, 'status')
                ->label('Trans Type', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Trans Type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$sponsorJS = <<< JS

const autoCapitalize = (e) => {
    e.value = e.value.toUpperCase()
}

JS;
$this->registerJs($sponsorJS, yii\web\View::POS_END);
