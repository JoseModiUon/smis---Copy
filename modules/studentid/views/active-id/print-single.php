<?php


/* @var $this \yii\web\View */

/* @var $model app\modules\studentid\models\StudentId */

/* @var $idRequest array|null|yii\db\DataReader */


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Manage student ID', 'url' => ['/student-id']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("@web/css/student-id.css");
$formatter = \Yii::$app->formatter;


$names = $idRequest['full_name'];
$regNo = $idRequest['student_number'];
$idNo = $idRequest['id_pp'];
$category = $idRequest['std_category_name'];
$courseName = $idRequest['prog_full_name'];
$faculty = ArrayHelper::getValue($idRequest, 'faculty', 'FACULTY NAME HERE');

$issueDate = $model->issuance_date;
$expiryDate = $model->valid_to; //date('Y-m-d', strtotime('+1 year', strtotime($model->issuance_date)));


$generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
$regBarCode = $generator->getBarcode($regNo, $generator::TYPE_CODE_128, 1);

$filename = str_replace('/', '', $regNo);
$barcodeFileName = "images/barcodes/$filename.svg";
file_put_contents($barcodeFileName, $regBarCode);

?>
<<<<<<< HEAD
<<<<<<< HEAD


<div id="PrintThis">
    <div class="id-container id-container-front mt-2" id="id-front">

=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f


<div id="PrintThis">
    <div class="id-container id-container-front mt-2" id="id-front">

<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::img("@web/images/uon_id_front_main.jpg", [
            'alt' => 'Front background',
            'class' => 'id-card-bg',
        ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD

        <div class="text-uppercase id-card-title"><?= $category ?> card</div>
        <div class="id-card-names"><?= $names ?></div>
        <div class="id-card-reg-no">Reg No: <?= $regNo ?></div>
        <div class="id-card-id-no">ID/PP No: <?= $idNo ?></div>
        <div class="text-uppercase id-card-course"><?= $courseName ?></div>
        <div class="text-uppercase id-card-faculty"><?= $faculty ?></div>

=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

        <div class="text-uppercase id-card-title"><?= $category ?> card</div>
        <div class="id-card-names"><?= $names ?></div>
        <div class="id-card-reg-no">Reg No: <?= $regNo ?></div>
        <div class="id-card-id-no">ID/PP No: <?= $idNo ?></div>
        <div class="text-uppercase id-card-course"><?= $courseName ?></div>
        <div class="text-uppercase id-card-faculty"><?= $faculty ?></div>

<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::img("@web/images/passport.png", [
            'alt' => 'Passport photo',
            'class' => 'passport-photo',
            'width' => '100',
            'height' => '120'
        ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD

=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======

>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::img("@web/images/signature.png", [
            'alt' => 'Passport photo',
            'class' => 'signature',
            'width' => '150',
        ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD
    </div>
    <hr/>
    <div class="id-container id-container-back" id="id-back">
=======
    </div>
    <hr/>
    <div class="id-container id-container-back" id="id-back">
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
    </div>
    <hr/>
    <div class="id-container id-container-back" id="id-back">
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
        <?= Html::img("@web/images/fountain_logo.jpg", [
            'alt' => 'REar background',
            'class' => 'id-card-bg',
        ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD

        <div class="id-card-issue-date">Issued: <?= $formatter->asDate($issueDate) ?></div>
        <div class="id-card-expiry-date">Expires: <?= $formatter->asDate($expiryDate) ?></div>
        <div class="id-card-barcode">
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f

        <div class="id-card-issue-date">Issued: <?= $formatter->asDate($issueDate) ?></div>
        <div class="id-card-expiry-date">Expires: <?= $formatter->asDate($expiryDate) ?></div>
        <div class="id-card-barcode">
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
            <?= Html::img("@web/$barcodeFileName", [
                'alt' => 'Reg number barcode',
                'class' => 'barcode',
            ]) ?>
<<<<<<< HEAD
<<<<<<< HEAD
            <div class="text-center"><?= $regNo ?></div>
        </div>
    </div>
</div>

<div id="PrintThis" class="mt-2">
=======
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
            <div class="text-center"><?= $regNo ?></div>
        </div>
    </div>
</div>

<div id="PrintThis" class="mt-2">
<<<<<<< HEAD
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
    <?= \yii2assets\printthis\PrintThis::widget([
        'htmlOptions' => [
            'id' => 'PrintThis',
            'btnClass' => 'btn btn-info',
            'btnId' => 'btnPrintThis',
            'btnText' => 'Print ID',
            'btnIcon' => 'fa fa-print'
        ],
        'options' => [
            'importCSS' => true,
            'importStyle' => false,
            'pageTitle' => "",
            'removeInline' => false,
            'printDelay' => 333,
            'header' => null,
            'formValues' => true,
        ]
    ]); ?>
<<<<<<< HEAD
<<<<<<< HEAD
</div>
=======
</div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
=======
</div>
>>>>>>> 95ef1dfb302531aa7cc0d2ad75fed7d9bf15ad0f
