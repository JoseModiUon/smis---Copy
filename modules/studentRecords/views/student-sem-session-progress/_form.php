<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\studentRecords\models\AcademicSessionSemester;
use app\modules\studentRecords\models\AcademicProgress;
use kartik\select2\Select2;
use app\modules\studentRegistration\models\AcademicLevel;
use app\modules\studentRecords\models\StudentSemesterSessionStatus;
use yii\bootstrap5\Modal;
use app\modules\studentRecords\models\AcademicSession;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\StudentSemSessionProgress $model */
/** @var yii\widgets\ActiveForm $form */
$request = Yii::$app->request;
$regno = $request->get('registration_number');
$name = $request->get('full_names');
?>

<div class="student-sem-session-progress-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">



        <?php
            $sp = AcademicSession::find()->select(['acad_session_id', 'acad_session_name'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'acad_session_id', 'acad_session_name');
            echo $form
                ->field($model->academicProgress, 'acad_session_id')
                ->label('Academic Session', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Academic Session ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>

        </div>
        <div class="col-md-6">
            <?php
            $sp = AcademicSessionSemester::find()->select(['acad_session_semester_id', 'acad_session_semester_desc'])->asArray()->all();
            $data = ArrayHelper::map($sp, 'acad_session_semester_id', 'acad_session_semester_desc');
            echo $form
                ->field($model, 'semester_progress')
                ->label('Session Semester', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Progress Status ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-md-5">
            
        <?php
            $p = StudentSemesterSessionStatus::find()->select(['status_id', 'status_name'])->asArray()->all();
            $dat = ArrayHelper::map($p, 'status_id', 'status_name');
            echo $form
                ->field($model, 'rep_status_id')
                ->label('Status Name', ['class' => 'mb-2 fw-bold'])
                ->widget(Select2::class, [
                    'data' => $dat,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Status Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>

        <div class="col-md-12">
            


        <?= $form->field($model, 'sem_progress_number')->textInput() ?>



        </div>

    </div>


    <?php $form->field($model, 'semester_progress')->textInput() ?>


    


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    
    <?php ActiveForm::end(); ?>

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

echo 'Are you sure you want to perform an update on this record?';

Modal::end();

// Your JavaScript code
$js = new JsExpression("
    $(document).ready(function() {
        setTimeout(function() {
            $('#w2-success-0').fadeOut();
        }, 1000); // 2000 milliseconds = 2 seconds
    });
");

// Register the JavaScript
$this->registerJs($js);

?>



    <?php $form->field($model, 'registration_date')->textInput() ?>

    <?php $form->field($model, 'academic_progress_id')->textInput() ?>

    <?php $form->field($model, 'sem_progress_number')->textInput() ?>

    <?php $form->field($model, 'billable')->textInput() ?>

    <?php $form->field($model, 'rep_status_id')->textInput() ?>

    <?php $form->field($model, 'prom_status_id')->textInput() ?>

    <?php $form->field($model, 'reporting_snyc_status')->checkbox() ?>

    <?php $form->field($model, 'semester_progress')->textInput() ?>

    <?php $form->field($model, 'promotion_status')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'prog_curriculum_semester_id')->textInput() ?>

    <?php $form->field($model, 'userid')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'user_action')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'last_update')->textInput() ?>
