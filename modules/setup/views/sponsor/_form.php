<?php
/** author Jeff Rureri <wahome4jeff@gmail.com>
 * Date: 30/10/2023
*/

use app\models\OrgSponsor;
use app\models\OrgCountry;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OrgSponsor $model */
/** @var yii\widgets\ActiveForm $form */


?>

<div class="org-sponsor-form">





    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'sponsor_name')->textInput(['maxlength' => true])?>
<br/>

    <?php
    $countries = OrgCountry::find()->select(['country_code', 'country_name'])->orderBy('country_name')->asArray()->all();
$data = ArrayHelper::map($countries, 'country_code', function ($model) {
    return $model['country_code'] . ' - ' . $model['country_name'];
});
echo $form->field($model, 'country_code')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Select a country ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
?>
	<br/>
	<?php
    $status = array(1 => "Active", 0 => "Inactive");


echo $form
    ->field($model, 'status')
    ->label('Status')
    ->widget(Select2::classname(), [
        'data' => $status,
        'language' => 'en',
        'options' => ['placeholder' => 'Select status'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>
	<br/>
    
    <div class="form-group">
        <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<br/>
</div>
