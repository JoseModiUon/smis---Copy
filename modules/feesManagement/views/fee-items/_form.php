<?php

use app\models\FeeItems;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\FeeItems $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fee-items-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'fee_description')
                ->textInput(['oninput' => 'autoCapitalize(this)']) // Add the oninput attribute here
                ->label('Fee Description', ['class' => 'mb-2 fw-bold'])

            ?>

        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $data = [
                '1' => '1',
                '2' => '2',
            ];
            echo $form
                ->field($model, 'priority')
                ->label('Priority', ['class' => 'mb-2 fw-bold'])
                ->label('Priority', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Priority ...'],
                    'options' => ['placeholder' => 'Select Priority ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>


        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'naration')
                ->textarea()
                ->label('Narration', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>
    <div class="row mb-2">
    <div class="row mb-2">
        <div class="col-md-12">
            <?= $form
                ->field($model, 'naration')
                ->textarea()
                ->label('Narration', ['class' => 'mb-2 fw-bold'])
            ?>

        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $data = [
                'ADMIN' => 'ADMIN',
                'COURSE' => 'COURSE',
            ];
            echo $form
                ->field($model, 'fee_type')
                ->label('Fee Type', ['class' => 'mb-2 fw-bold'])
                ->label('Fee Type', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Fee Type ...'],
                    'options' => ['placeholder' => 'Select Fee Type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>


        </div>
    </div>

    <div class="row mb-2">

    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $data = [
                'YES' => 'YES',
                'NO' => 'NO',
            ];
            $data = [
                'YES' => 'YES',
                'NO' => 'NO',
            ];
            echo $form
                ->field($model, 'publish')
                ->label('Published?', ['class' => 'mb-2 fw-bold'])
                ->label('Published?', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select if Published ...'],
                    'options' => ['placeholder' => 'Select if Published ...'],
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
            $types = FeeItems::find()->select(['fee_code', 'fee_description'])->asArray()->all();
            $data = ArrayHelper::map($types, 'fee_code', 'fee_description');
            echo $form
                ->field($model, 'fee_code_alias')
                ->label('Fee Code Alias', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Fee Code Alias ...'],
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$itemsJS = <<< JS

const autoCapitalize = (e) => {
    e.value = e.value.toUpperCase()
}

JS;
$this->registerJs($itemsJS, yii\web\View::POS_END);
