<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\OrgInstitutionSetupSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="org-institution-setup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'institution_setup_id') ?>

    <?= $form->field($model, 'unit_id') ?>

    <?= $form->field($model, 'logo_url') ?>

    <?= $form->field($model, 'favicon_url') ?>

    <?= $form->field($model, 'motto') ?>

<<<<<<< HEAD
<<<<<<< HEAD
    <?php // echo $form->field($model, 'email')?>

    <?php // echo $form->field($model, 'phone')?>

    <?php // echo $form->field($model, 'website')?>

    <?php // echo $form->field($model, 'postal_address')?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'postal_address') ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
