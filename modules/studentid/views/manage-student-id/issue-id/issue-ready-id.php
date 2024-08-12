<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentIdDetails */
/* @var $form kartik\form\ActiveForm */

?>
<<<<<<< HEAD
<<<<<<< HEAD

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'remarks')->textarea(['rows' => 4]) ?>
    </div>

    <div class="card-footer">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord ? 'btn btn-success col-12' : 'btn btn-primary col-12'
            ]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'remarks')->textarea(['rows' => 4]) ?>
    </div>

    <div class="card-footer">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord ? 'btn btn-success col-12' : 'btn btn-primary col-12'
            ]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
