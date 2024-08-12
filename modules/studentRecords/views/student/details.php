<?php

/* @var $this yii\web\View */

/* @var $student_id int */

/* @var $model \app\models\Student */

use app\modules\studentRecords\components\StudentTabs;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
//use Stidges\CountryFlags\CountryFlag;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

$this->title = $model->surname . ' ' . $model->other_names;
$this->params['breadcrumbs'][] = ['label' => 'Student Records', 'url' => ['/student-records']];
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label' => '&nbsp;',
        'options' => ['id' => 't-ph'],
        'content' => '<div class="text-primary fs-5 fw-bold">Click on any tab above to View the info</div>',
        'linkOptions' => ['class' => 'disabled'],
    ],
    [
        'label' => '<i class="bi bi-person-dash"></i> Personal Info',
        'content' => '<div class="text-primary fs-5 fw-bold"></div>',
        'options' => ['id' => 't-student'],
        'linkOptions' => ['data-url' => Url::to(['personal-info', 'id' => $student_id])],
    ],
//    [
//        'label' => '<i class="bi bi-journal-medical"></i> Fees Statement',
//        'options' => ['id' => 't-fees'],
//        'linkOptions' => ['data-url' => Url::to(['fees-statement', 'id' => $STUDENT_ID])]
//    ],
    [
        'label' => '<span class="d-print-none "> <i class="bi bi-journal-medical"></i> Programme</span>',
        'options' => ['id' => 't-programme'],
        'linkOptions' => ['data-url' => Url::to(['programme', 'id' => $student_id])]
    ],
    [
        'label' => '<i class="bi bi-person-lines-fill"></i> Contact Info',
        'options' => ['id' => 't-contact'],
        'linkOptions' => ['data-url' => Url::to(['contact-info', 'id' => $student_id])]
    ],
    [
        'label' => '<i class="bi bi-telephone-forward-fill"></i> Next of Kin',
        'options' => ['id' => 't-nok', 'class' => 'd-print-none '],
        'linkOptions' => ['data-url' => Url::to(['next-of-kin', 'id' => $student_id])]
    ],
];

?>
    <div class="bg-primary bg-opacity-10 border-primary border border-2 border-opacity-25 rounded">
        <div class="d-flex flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <div class="p-md-2 fs-1">
                        <img src="<?= $model->avatar() ?>"
                             style="width: 60px;" alt="Profile" class="rounded stdn-avtr" />
                </div>
                <div class="p-md-2 p-1">
                    <h5 class="stdn-name"><?= $model->surname . ' ' . $model->other_names; ?></h5>
                    <div class="stdn-sponsor text-muted"><?= $model->sponsor ?></div>
                </div>
            </div>
            <div class="d-flex flex-column">

                <h5>Blood Group</h5>
                <div class="text-danger">
                    <i class="fa-solid fa-droplet"></i>
                    <span class="stdn-blood"><?= $model->blood_group ?></span>
                </div>
            </div>
            <div class="d-flex flex-column">
                <h5>Contact</h5>
                <div class="text-muted">
                    <i class="bi bi-phone-flip text-primary p-1 rounded-circle"></i>
                    +254-000-123-456
                </div>

            </div>
            <div class="rounded p-lg-2 p-1">
                <div class="d-flex flex-md-row align-items-center">
                    <div class="d-flex flex-column align-items-center px-lg-3 px-md-2 px-1" id="border-right">
                        <div class="h5" id="count">Nationality</div>
                        <p class="h5 text-success stdn-nationality">
                            <?= $model->countryCode->nationality ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <?=StudentTabs::main($items) ?>
        </div>
    </div>

<?php
$avaUrl = Url::to(['photo','id'=>$model->student_id]);
Modal::begin([
    'id' => 'stdn-photo-upload',
    'title' => 'Photo Upload',
]);
$form1 = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'] // important
]);
echo FileInput::widget([
    'name' => 'stdn-avatar-input',
    'options' => ['multiple' => false],

    'pluginOptions' => [
        "maxFileCount"=> 1,
        'allowedFileExtensions' => ["jpg", "png",],
        'initialPreviewAsData'=> false,
        'overwriteInitial'=> true,
        'uploadUrl' => Url::to(['photo-upload', 'id' => str_replace('/', '', $model->student_number)]),
    ],
    'pluginEvents' => [
        "fileuploaded" => "function(event, previewId, index, fileId) {
            getStudentImage();
            $('#stdn-photo-upload button.fileinput-remove').trigger('click');
            $('#stdn-photo-upload').modal('toggle');
        }",
    ],
]);
ActiveForm::end();
Modal::end();

$this->registerJs(js: <<<JS
async function getStudentImage(){
    photoAnimate(true);
    let response = await fetch('$avaUrl');
    if (response.ok) {
      let txt = await response.text();
      let phts = document.getElementsByClassName('stdn-avtr');
      for( const p of phts ){
          p.src = txt;
      }
    }
    photoAnimate(false);
}
const photoAnimate = (on=true)=>{
     const phts = document.getElementsByClassName('stdn-avtr');
     const cls = 'fa-beat-fade';
      for( const p of phts ){
          on ? p.classList.add(cls) : p.classList.remove(cls);
      }
}
JS
);
