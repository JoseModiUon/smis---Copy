<?php

use app\modules\feesManagement\models\BillingType;
use kartik\builder\Form;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\ProgrammeCurriculum $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="programme-curriculum-form">

    <?php $form = ActiveForm::begin(); ?>

<?php
    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'compactGrid'=>true,
        'attributes'=>[
            'billing_type_id'=>[                
                'type'=>Form::INPUT_WIDGET, 
                'widgetClass'=>'\kartik\select2\Select2', 
                'options' => [
                    'data' => ArrayHelper::map(BillingType::find()->all(), 'billing_type_id', 'billing_type_desc')],
                'hint'=>'Select and billing type',
                'label' => false,
            ],

            'actions'=>[
                'type'=>Form::INPUT_RAW, 
                'value'=>'<div style="text-align: right; margin-top: 20px">' . 
                    Html::resetButton('Reset', ['class'=>'btn btn-secondary btn-default']) . ' ' .
                    Html::button(Yii::t('app', 'Submit'), ['type'=>'submit', 'class'=>'btn btn-primary']) . 
                    '</div>'
            ],
        ]
    ]);
    $JsFunction = new \yii\web\JsExpression(

        "
        $('form').submit(function (event) {
            event.preventDefault();             
          });
        "
    
    );

    ?>

    <?php ActiveForm::end(); ?>
<?php
$this->registerJs($JsFunction);
?>
</div>
