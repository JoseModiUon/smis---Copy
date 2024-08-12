<?php

use app\models\StudentProgrammeCurriculum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\studentRecords\models\search\AcademicProgressSearch $model */
/** @var yii\widgets\ActiveForm $form */
$request = Yii::$app->request;
$registration_number = $request->get('registration_number') ?? '';

// Fetch registration numbers from the database and encode them as a JSON array
$studProg = StudentProgrammeCurriculum::find()->select('registration_number')->column();
$dataset = json_encode($studProg);
//validation of registration number
$this->registerJs(<<<JS
document.getElementById('academic-progress-search').addEventListener('submit', function(event) {
    const dataset = $dataset;
    const inputValue = document.getElementById('registration_number').value;
    const errorElement = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');

    if (!dataset.includes(inputValue)) {
        event.preventDefault(); // Prevent form submission
        errorElement.textContent = 'The Registration Number does not exist.';
        submitButton.disabled = true; // Disable the submit button
    } else {
        errorElement.textContent = ''; // Clear any previous error message
        submitButton.disabled = false; // Enable the submit button
    }
});

// Initial button state check
document.getElementById('registration_number').addEventListener('input', function() {
    const dataset = $dataset;
    const inputValue = this.value;
    const errorElement = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');

    if (dataset.includes(inputValue)) {
        errorElement.textContent = ''; // Clear error message
        submitButton.disabled = false; // Enable submit button
    } else {
        errorElement.textContent = 'The Registration Number does not exist';
        submitButton.disabled = true; // Disable submit button
    }
});
JS);
?>



<div class="academic-progress-search">

    <?php $form = ActiveForm::begin([
        'id' => 'academic-progress-search',
        'action' => Yii::$app->request->url,
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input value="<?= Html::encode($registration_number) ?>" type="text" class="form-control" name="registration_number" id="registration_number" aria-describedby="helpId" placeholder="Enter your Registration Number">
                <span id="error-message" class="text-danger"></span>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'submit-button']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
