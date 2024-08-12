<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\feesManagement\models\ProgrammeCurriculum $model */

$this->title = $model->orgProgrammes->prog_code .' - '.$model->prog_curriculum_desc;
// $this->title = 'UPDATE PROGRAMME BILLING TYPE';
$this->params['breadcrumbs'][] = ['label' => 'Programme Curriculums', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="programme-curriculum-update">
    <div class="row justify-content-center">
        <div class="col-md-8">        
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                    <p class="text-danger p-3 text-center text-bold">This could result in higher or lower charges depending on the billing structure.</p>
                        <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php
    $JsFunction = new \yii\web\JsExpression(

        "
        $('form').on('beforeSubmit', function (e) {
            // Show the confirmation modal
            $('.modal-dialog').addClass('modal-dialog-centered');
            $('#confirmation-modal').modal('show');
        
            // Prevent the default form submission behavior
            return false;
        });
        
        // When the confirmation button is clicked
        $('#confirm-btn').click(function () {
            // Hide the confirmation modal
            $('#confirmation-modal').modal('hide');
        
            // Submit the form
            $('form').off('submit').submit();
        });

        $('#confirmation-modal').on('click', '[data-dismiss=\"modal\"]', function () {
            // Hide the confirmation modal
            $('#confirmation-modal').modal('hide');
        });
        

        "
    
    );
?>


<?php
$this->registerJs($JsFunction);
?>

<?php
Modal::begin([
    'id' => 'confirmation-modal',
    'title' => 'Confirmation',
    'size' => Modal::SIZE_DEFAULT,
    "clientOptions" => [
        "tabindex" => false,
        "backdrop" => "static",
        "keyboard" => false,
    ],
    'footer' => '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                 <button type="button" class="btn btn-primary" id="confirm-btn">Confirm</button>',               
]);

echo 'Are you sure you want to update the billing type of this record?';

Modal::end();

?>