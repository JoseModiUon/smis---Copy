<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrgKuccpsProgMap */

$this->title = 'Create Kuccps Program Map';
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Kuccps Program Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-kuccps-prog-map-create">

    <div class="card" >
        <div class="card-body">
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])  ?>


            <div class="row mb-3">
                <div class="col-md-12">
<<<<<<< HEAD
<<<<<<< HEAD
                    <?=
                        $form
                        ->field($model, 'batchFile')
                        ->label('KUCCPS Program Map', ['class' => 'mb-2 fw-bold form-label'])
                        ->fileInput(['class' => 'form-control'])
?>
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
                    <?= 
                        $form
                        ->field($model, 'batchFile')
                        ->label('KUCCPS Program Map', ['class'=>'mb-2 fw-bold form-label'])
                        ->fileInput(['class' => 'form-control']) 
                    ?>
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="d-flex justify-content-end">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
 
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>

</div>
